<template>
  <div v-if="battle" class="container">
    <h1> {{ battle.name }} </h1>

    <card :title="battle.name">
      <div class="sample-description">
        {{ battle.description }}
      </div>
      <div class="sample-download">
        <a :href="'/api/samples/'+battle.sample_id">Download Sample </a>
      </div>

      <button class="btn btn-primary" data-toggle="collapse" data-target="#entries">
        Show Entries
      </button>

      <div id="entries" class="collapse">
        <ul class="list-group">
          <li v-for="entry in battleEntries" :key="entry.id" class="list-group-item">
            {{ entry.user.name }} - {{ entry.sample.filename }}
          </li>
        </ul>
      </div>

      <div>
        <button v-if="user && hasSubmitted" class="btn btn-info" @click="editEntry">
          Edit Entry
        </button>
        <button v-if="user && !hasSubmitted" class="btn btn-info" @click="submitEntry">
          Submit an Entry
        </button>
        <router-link v-if="!user" class="btn btn-info" :to="{name: 'login'}">
          Please login to submit an entry.
        </router-link>
        <create-entry :battle-id="battle.id" />
      </div>
    </card>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
  computed: {
    battle () {
      if (this.battles == null) return {}
      return this.battles.find(b => b.id === parseInt(this.$route.params.id))
    },
    battleEntries () {
      return this.entries(this.$route.params.id)
    },
    hasSubmitted () {
      if (!this.battleEntries) return false
      return (this.user && this.battleEntries.find(e => e.user_id === this.user.id))
    },
    ...mapGetters({
      battles: 'battles/battles',
      entries: 'battles/entries',
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
  methods: {
    update () {
      // no need to update battles if already loaded by battels page
      if (this.battles == null) {
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
      console.log(this.battleEntries)
      console.log(this.entries(this.$route.params.id))
      this.$modal.show('create-entry')
    },
    editEntry () {
      // TODO
      console.log('edit entry')
    }
  }
}

</script>
