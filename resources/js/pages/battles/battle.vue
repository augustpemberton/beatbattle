<template>
  <div v-if="battle">
    <div id="title-row">
      <h1 class="float-left">
        {{ battle.name }}
      </h1>
      <h2 v-if="battle.winner">
        won by {{ battle.winner.name }}
      </h2>
      <div v-if="user && battle.user && battle.user.id === user.id" class="float-right">
        <button :disabled="hasBattleEnded" class="btn btn-info" @click="$modal.show('edit-battle')">
          <fa icon="cog" fixed-width />
        </button>
        <edit-battle :battle="battle" />
      </div>
      <div v-else-if="battle.user" class="float-right align-bottom">
        created by {{ battle.user.name }}
      </div>
      <div class="clearfix" />
    </div>

    <hr>

    <div class="row battle-info">
      <div class="col-sm">
        <!-- Countdown Timer -->
        <div class="countdown-timer">
          <battle-countdown
            v-if="!hasBattleEnded"
            :start-time="battle.start_time"
            :end-time="battle.end_time"
            start-label="Battle starts in"
            end-label="Battle ends in"
            @timer-start="battleStart"
            @timer-end="battleEnd"
          />
          <!-- no need for start time or start label, as it will always started if active -->
          <battle-countdown
            v-else-if="battle.status != battle_status.finished"
            :end-time="battle.voting_time"
            end-label="Voting ends in"
            @timer-end="votingEnd"
          />
        </div>

        <!-- Description -->
        <div class="sample-description">
          {{ battle.description }}
        </div>

        <!--Submissions times -->
        <div class="submission-times">
          <div v-if="hasBattleEnded">
            status: submission closed
          </div>
          <div v-else-if="hasBattleStarted">
            status: submissions open
            submissions close on {{ battle.end_time | moment('dddd, MMMM Do [at] h:mm a') }}
            <br>
          </div>
          <div v-else>
            status: not started yet
          </div>
        </div>

        <!-- Submit Entry -->
        <div v-if="hasBattleStarted && !hasBattleEnded">
          <button v-if="user && userEntry" class="btn btn-info" @click="editEntry">
            Edit Entry
          </button>
          <button v-if="user && !userEntry" class="btn btn-info" @click="submitEntry">
            Submit an Entry
          </button>
          <router-link v-if="!user" class="btn btn-info" :to="{name: 'login'}">
            Please login to submit an entry.
          </router-link>
          <create-entry :battle="battle" />
          <edit-entry v-if="user && userEntry" :entry="userEntry" />
        </div>
      </div>
      <div class="col-sm-4">
        <!-- Sample Download -->
        <card title="samples">
          <ul class="list-group list-group-flush">
            <li v-for="sample in battle.samples" :key="sample.id" class="list-group-item">
              <a :href="'/api/samples/'+sample.id">{{ sample.filename }}</a>
            </li>
          </ul>
        </card>
      </div>
    </div>
    <div v-if="hasBattleStarted" class="row entries-info">
      <div class="col-sm">
        <!-- Entries -->
        <h2>entries</h2>
        <entry-list
          ref="entry_list"
          :entries="battleEntries"
          :has-battle-ended="hasBattleEnded"
          :has-voting-ended="hasVotingEnded"
          :winner="battle.winner"
        />
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
  data () {
    return {
      battle_status: window.config.battle_status
    }
  },
  computed: {
    battle () {
      if (this.battles == null) return {}
      return this.battles.find(b => b.id === parseInt(this.$route.params.id))
    },
    hasBattleStarted () {
      if (!this.battle) return false
      var battleStartTime = this.$moment(this.battle.start_time)
      return (this.$moment() > battleStartTime)
    },
    hasBattleEnded () {
      if (!this.battle) return false
      var battleEndTime = this.$moment(this.battle.end_time)
      return (this.$moment() > battleEndTime)
    },
    hasVotingEnded () {
      if (!this.battle) return false
      var votingEndTime = this.$moment(this.battle.voting_time)
      return (this.$moment() > votingEndTime)
    },
    battleEntries () {
      return this.entries(this.$route.params.id)
    },
    userEntry () {
      if (!this.battleEntries || !this.user) return null
      return this.battleEntries.find(e => e.user.id === this.user.id)
    },
    ...mapGetters({
      battles: 'battles/battles',
      entries: 'battles/entries',
      votes: 'battles/votes',
      user: 'auth/user'
    })
  },
  beforeRouteUpdate () {
    // update entries if route changes
    this.update()
  },
  created () {
    this.update()
  },
  mounted () {
    window.echo.channel('entries')
      .listen('NewEntry', (e) => {
        console.log('New Entry')
        console.log(e)
        // use $route.params.id instead of battle.id
        // as we dont have to wait for the battle to load
        if (e.battle_id === this.$route.params.id) {
          this.$store.dispatch('battles/fetchEntries', this.$route.params.id)
        }
      })
      .listen('DeleteEntry', (e) => {
        this.$store.commit('battles/DELETE_ENTRY', { userid: e.user_id, battleid: e.battle_id })
      })
      .listen('BattleFinished', (e) => {
        console.log('battle finished')
        this.$refs.entry_list.animateEntries()
        this.$store.dispatch('battles/fetchBattle', this.$route.params.id)
      })
  },
  methods: {
    update () {
      // no need to update battles if already loaded by battles page
      if (this.battles.length === 0) {
        this.updateBattles()
      }
      this.updateEntries()
    },
    updateBattles () {
      this.$store.dispatch('battles/fetchBattles')
    },
    updateEntries () {
      this.$store.dispatch('battles/fetchEntries', this.$route.params.id)
    },
    submitEntry () {
      this.$modal.show('create-entry')
    },
    editEntry () {
      this.$modal.show('edit-entry')
    },
    battleStart () {
      if (!this.hasBattleStarted) {
        this.$store.dispatch('battles/fetchBattle', this.$route.params.id)
      }
    },
    battleEnd () {
      if (!this.hasBattleEnded) {
        this.$store.dispatch('battles/fetchBattle', this.$route.params.id)
      }
    },
    votingEnd () {
      if (!this.hasVotingEnded) {
        this.$store.dispatch('battles/fetchBattle', this.$route.params.id)
      }
    }
  }
}
</script>

<style lang="scss" scoped>
.container {
  min-height: 100vh;
}
.battle-info {
  min-height: 40vh;
}
.entries-info {
  height: 100%;
}
</style>
