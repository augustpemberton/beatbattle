<template>
  <div v-if="battle" class="container">
    <h1> {{ battle.name }} </h1>
    <card :title="battle.name">
      {{ battle.description }}
      <span class="sample-download">
        <a :href="'/api/samples/'+battle.sample_id">Download Sample </a>
      </span>
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
    ...mapGetters({
      battles: 'battles/battles',
      entries: 'battles/entries'
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
      this.$store.dispatch('battles/fetchEntries', {
        id: this.$route.params.id
      })
    }
  }
}

</script>
