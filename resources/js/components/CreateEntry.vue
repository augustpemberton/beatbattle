<template>
  <modal height="auto" name="create-entry">
    <form>
      <div class="form-group">
        <label for="sample-upload">Sample</label>
        <sample-upload id="sample-upload" v-model="entry.sample_id" />
      </div>
      <div class="form-group">
        <label for="description-input">Notes</label>
        <input id="description-input" v-model="entry.notes" class="form-control" type="text">
      </div>
      <div class="form-check">
        <input id="listenable-early-input" v-model="entry.listenable_early" type="checkbox" class="form-check-input">
        <label for="listenable-early-input">Allow other users to listen to your submission before the game is finished?</label>
      </div>
      <button
        class="btn btn-primary"
        @click.prevent="submitEntry()"
      >
        Submit Entry
      </button>
    </form>
  </modal>
</template>
<script>
import axios from 'axios'
export default {
  name: 'CreateEntry',
  props: {
    battleId: {
      type: Number,
      default: 0
    }
  },
  data () {
    return {
      entry: {
        sample_id: 0,
        notes: '',
        listenable_early: false
      }
    }
  },
  methods: {
    async submitEntry () {
      this.entry.battle_id = this.battleId
      axios.post('/api/entries', this.entry)
        .then(async (response) => {
          console.log(response)
          await this.$store.dispatch('battles/fetchEntries')
        })
    }
  }
}
</script>

<style scoped>
form {
  padding: 20px;
}
</style>
