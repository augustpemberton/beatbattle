<template>
  <div>
    <p v-if="votes($route.params.id)">
      {{ votes($route.params.id).length }} {{ 'vote' | pluralize(votes($route.params.id).length) }} cast so far 
    </p>
    <div v-if="entries" id="entries">
      <span v-if="entries.length == 0">no entries yet.</span>
      <transition-group class="list-group" name="entries" tag="ul">
        <li v-for="entry in sortedEntries" :key="entry.id" class="list-group-item clearfix" :class="{'user-entry': isUserEntry(entry)}">
          {{ entry.user.name }} - {{ entry.sample.filename }}
          <vote-button
            class="vote-btn float-right"
            :entry="entry"
            :value="isActiveVote(entry)"
            @click="vote(entry)"
          />
        </li>
      </transition-group>
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
  },
  methods: {
    isUserEntry (entry) {
      return (entry.user.id === this.user.id)
    },
    vote (entry) {
      if (this.isActiveVote(entry)) {
        this.removeVote(entry)
      } else {
        this.addVote(entry)
      }
    },
    async addVote (entry) {
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
    }
  }
}
</script>

<style lang="scss" scoped>
.entries-enter-active, .entries-leave-active {
  transition: all 0.5s;
}
.entries-enter, .entries-leave-to {
  opacity: 0;
  transform: translateX(50px);
}

.user-entry {
  border-left: 5px solid blue;
}

.vote-btn {
  cursor:pointer;
  transition: color 0.2s;
}

.vote-btn.active {
  color: red;
}
</style>
