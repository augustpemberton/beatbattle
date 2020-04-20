<template>
  <div>
    <create-battle />
    <button v-if="user" class="btn btn-info" @click="$modal.show('create-battle')">
      Create Battle
    </button>
    <router-link v-else tag="button" class="btn btn-info" :to="'/login'">
      Please login to create a battle.
    </router-link>

    <!-- Active Games -->
    <div class="upcoming-games">
      <h1> Active </h1>
      <hr>
      <battle-list :battles="activeGames" />
    </div>
    <!-- Voting Games -->
    <div class="voting-games">
      <h1> Voting </h1>
      <hr>
      <battle-list :battles="votingGames" />
    </div>
    <!-- Upcoming Games -->
    <div class="upcoming-games">
      <h1> Upcoming </h1>
      <hr>
      <battle-list :battles="upcomingGames" />
    </div>
    <!-- Finished Games -->
    <div class="finished-games">
      <h1> Finished </h1>
      <hr>
      <battle-list :battles="finishedGames" />
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
  computed: {
    activeGames () {
      if (!this.battles) return []
      console.log('test')
      return (this.battles.filter(b => {
        return (
          this.$moment(b.start_time) < this.$moment() &&
          this.$moment(b.end_time) > this.$moment()
        )
      }))
    },
    votingGames () {
      if (!this.battles) return []
      return (this.battles.filter(b => {
        return (
          this.$moment(b.end_time) < this.$moment() &&
          this.$moment(b.voting_time) > this.$moment()
        )
      }))
    },
    upcomingGames () {
      if (!this.battles) return []
      return (this.battles.filter(b => {
        return (
          this.$moment(b.start_time) > this.$moment()
        )
      }))
    },
    finishedGames () {
      if (!this.battles) return []
      return (this.battles.filter(b => {
        return (
          this.$moment(b.voting_time) < this.$moment()
        )
      }))
    },
    ...mapGetters({
      user: 'auth/user',
      battles: 'battles/battles'
    })
  },
  mounted () {
    this.$store.dispatch('battles/fetchBattles')
    window.echo.channel('battles')
      .listen('NewBattle', () => {
        this.$store.dispatch('battles/fetchBattles')
      })
  }
}
</script>
