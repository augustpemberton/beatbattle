<template>
  <modal height="auto" name="create-battle">
    <form>
      <div class="form-group">
        <label for="name-input">Name</label>
        <input id="name-input" v-model="battle.name" class="form-control" type="text">
      </div>
      <div class="form-group">
        <label for="description-input">Description</label>
        <input id="description-input" v-model="battle.description" class="form-control" type="text">
      </div>
      <div class="form-group">
        <label for="sample-upload">Sample</label>
        <sample-upload id="sample-upload" v-model="battle.sample_id" />
      </div>
      <div class="form-group">
        <label for="start-time-input">Start Time</label>
        <datetime id="start-time-input" v-model="battle.start_time" type="datetime" />
      </div>
      <div class="form-group">
        <label for="end-time-input">End Time</label>
        <datetime id="end-time-input" v-model="battle.end_time" type="datetime" />
      </div>
      <button
        class="btn btn-primary"
        @click.prevent="createBattle()"
      >
        Create Battle
      </button>
    </form>
  </modal>
</template>
<script>
import axios from 'axios'
export default {
  name: 'CreateBattle',
  data () {
    return {
      battle: {
        name: '',
        description: '',
        sample_id: 0,
        start_time: null,
        end_time: null
      }
    }
  },
  methods: {
    async createBattle () {
      this.battle['sample_id'] = 1
      axios.post('/api/battles', this.battle)
        .then(async (response) => {
          console.log(response)
          await this.$store.dispatch('battles/fetchBattles')
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
