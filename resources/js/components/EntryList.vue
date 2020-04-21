<template>
  <div>
    <p v-if="hasVotingEnded && votes($route.params.id)">
      {{ votes($route.params.id).length }} {{ 'vote' | pluralize(votes($route.params.id).length) }} cast
    </p>
    <p v-else-if="hasBattleEnded && votes($route.params.id)">
      {{ votes($route.params.id).length }} {{ 'vote' | pluralize(votes($route.params.id).length) }} cast so far
    </p>
    <div v-if="entries" id="entries">
      <span v-if="entries.length == 0">no entries yet.</span>
      <transition-group class="list-group" name="fade-right" tag="ul">
        <li
          v-for="entry in sortedEntries"
          :key="entry.id" class="list-group-item clearfix"
          :class="{'user-entry': isUserEntry(entry), 'winning-entry': isWinningEntry(entry)}"
        >
          <div class="row">
            <div class="col-sm-10">
              {{ entry.user.name }}
            </div>
            <div class="col-sm-2">
              <spinner ref="spinner" class="vote-count float-left" />
              <vote-button
                class="vote-btn float-right"
                :disabled="!(hasBattleEnded && !hasVotingEnded)"
                :entry="entry"
                :value="isActiveVote(entry)"
                @click="vote(entry)"
              />
            </div>
          </div>
          <div class="row">
            <audio-player
              class="player float-left"
              :url="'/api/samples/' + entry.sample.id"
              :height="100"
            />
          </div>
        </li>
      </transition-group>
      <button @click="animateEntries">
        Animate entries
      </button>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import axios from 'axios'

export default {
  name: 'EntryList',
  props: {
    entries: {
      type: Array,
      default: () => []
    },
    hasBattleEnded: {
      type: Boolean,
      default: false
    },
    hasVotingEnded: {
      type: Boolean,
      default: false
    },
    winner: {
      type: Object,
      default: null
    }
  },
  data () {
    return {
      showVotes: {}
    }
  },
  computed: {
    sortedEntries () {
      return this.entries.slice().sort((a, b) => {
        if (this.isUserEntry(a)) return -1
        return (this.$moment(a.date) > this.$moment(b.date))
      })
    },
    userVotes () {
      if (!this.user || !this.votes(this.$route.params.id)) return []
      return this.votes(this.$route.params.id).filter(v => v.user.id === this.user.id)
    },
    ...mapGetters({
      user: 'auth/user',
      votes: 'battles/votes'
    })
  },
  mounted () {
    this.$store.dispatch('battles/fetchVotes', this.$route.params.id)
    this.entries.forEach(entry => {
      this.$set(this.showVotes, entry.id, false)
    })
  },
  methods: {
    isUserEntry (entry) {
      if (!this.user) return false
      return (entry.user.id === this.user.id)
    },
    isWinningEntry (entry) {
      if (!this.winner || !this.entry) return false
      return (this.winner.id === this.entry.user.id)
    },
    vote (entry) {
      if (this.isActiveVote(entry)) {
        this.removeVote(entry)
      } else {
        this.addVote(entry)
      }
    },
    async addVote (entry) {
      if (!this.user) {
        this.$noty.error('Please login to vote.')
      }
      var data = {
        user_id: this.user.id,
        entry_id: entry.id
      }
      try {
        await axios.post('/api/votes', data)
        this.$store.dispatch('battles/fetchVotes', this.$route.params.id)
      } catch (e) {
        if (e.response.status === 422) {
          this.$noty.error('You have used up all of your votes.')
        }
      }
    },
    async removeVote (entry) {
      await axios.delete('/api/votes/' + entry.id)
      this.$store.dispatch('battles/fetchVotes', this.$route.params.id)
    },
    isActiveVote (entry) {
      return this.userVotes.find(v => v.entry.id === entry.id) != null
    },
    voteCount (entry) {
      if (!this.votes(this.$route.params.id)) return 0
      return this.votes(this.$route.params.id).filter(v => v.entry_id === entry.id).length
    },
    animateEntries () {
      var self = this
      var i = 0;
      (function f (i) {
        self.activateSpinner(self.sortedEntries[i], i)
        setTimeout(function () {
          if (i < self.sortedEntries.length - 1) f(i + 1)
        }, 2000)
      })(i)
    },
    activateSpinner (e, i) {
      this.$set(this.showVotes, e.id, true)
      this.$refs.spinner[i].spin(this.voteCount(e), 2)
    }
  }
}
</script>

<style lang="scss" scoped>
.fade-right-enter-active, .fade-right-leave-active {
  transition: all 0.5s;
}
.fade-right-enter, .fade-right-leave-to {
  opacity: 0;
  transform: translateX(50px);
}

.user-entry {
  border-left: 5px solid blue;
}

.winning-entry {
  border: 5px solid red;
}

.vote-btn {
  cursor:pointer;
  transition: color 0.2s;
}

.vote-btn.active {
  color: red;
}

.vote-count {
  margin-top: 2px;
}

.player {
  min-width: 100%;
}

</style>
