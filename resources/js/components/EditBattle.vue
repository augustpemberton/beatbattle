<template>
  <modal height="auto" name="edit-battle">
    <form @submit.prevent="editBattle" @keydown="battleUpdate.onKeydown($event)">
      <!-- Name -->
      <div class="form-group">
        <label for="name-input">Name</label>
        <input
          id="name-input"
          v-model="battleUpdate.name"
          :class="{ 'is-invalid': battleUpdate.errors.has('name') }"
          class="form-control"
          type="text"
        >
        <has-error :form="battleUpdate" field="name" />
      </div>

      <!-- Description -->
      <div class="form-group">
        <label for="description-input">Description</label>
        <input
          id="description-input"
          v-model="battleUpdate.description"
          :class="{ 'is-invalid': battleUpdate.errors.has('description') }"
          class="form-control"
          type="textarea"
        >
        <has-error :form="battleUpdate" field="description" />
      </div>

      <!-- Start Time -->
      <div class="form-group">
        <label for="start-time-input">Battle Start Time</label>
        <datetime
          id="start-time-input"
          v-model="battleUpdate.start_time"
          :min-datetime="$moment().toISOString()"
          :disabled="hasBattleStarted"
          :class="{ 'is-invalid': battleUpdate.errors.has('start_time') }"
          type="datetime"
        />
        <small v-if="hasBattleStarted" id="starttime-info" class="form-text text-muted">Battle has already started - start time cannot be edited.</small>
        <has-error :form="battleUpdate" field="start_time" />
      </div>

      <!-- End Time -->
      <div class="form-group">
        <label for="end-time-input">Submission Deadline</label>
        <datetime
          id="end-time-input"
          v-model="battleUpdate.end_time"
          :min-datetime="$moment().toISOString()"
          :disabled="hasBattleEnded"
          :class="{ 'is-invalid': battleUpdate.errors.has('end_time') }"
          type="datetime"
        />
        <small v-if="hasBattleEnded" id="endtime-info" class="form-text text-muted">Battle has already ended - end time cannot be edited.</small>
        <has-error :form="battleUpdate" field="end_time" />
      </div>

      <!-- Voting Time -->
      <div class="form-group">
        <label for="voting-time-input">Voting Deadline</label>
        <datetime
          id="voting-time-input"
          v-model="battleUpdate.voting_time"
          :min-datetime="$moment(battle.end_time).add(1, 'minutes').toISOString()"
          :class="{ 'is-invalid': battleUpdate.errors.has('voting_time') }"
          type="datetime"
        />
        <has-error :form="battleUpdate" field="voting_time" />
      </div>

      <!-- Submit -->
      <v-button :loading="battleUpdate.busy">
        Update Battle
      </v-button>

      <!-- Delete -->
      <button class="btn btn-warn" @click.prevent="deleteBattle">
        <fa icon="trash" fixed-width />
      </button>

      <!-- Timezone display -->
      <div v-if="user != null" class="form-timezone">
        <small id="timezone-info" class="form-text text-muted">Timezone: {{ timezone }}</small>
      </div>
    </form>
  </modal>
</template>
<script>
import { mapGetters } from 'vuex'
import Form from 'vform'
import Swal from 'sweetalert2'
import axios from 'axios'

export default {
  name: 'EditBattle',
  props: {
    'battle': {
      type: Object,
      default: null
    }
  },
  data () {
    return {
      battleUpdate: new Form({
        name: this.battle.name,
        description: this.battle.description,
        start_time: this.battle.start_time,
        end_time: this.battle.end_time,
        voting_time: this.battle.voting_time
      })
    }
  },
  computed: {
    hasBattleStarted () {
      var battleStartTime = this.$moment(this.battle.start_time)
      return (this.$moment() > battleStartTime)

      // return this.battle.status >= 1
    },
    hasBattleEnded () {
      var battleEndTime = this.$moment(this.battle.end_time)
      return (this.$moment() > battleEndTime)
    },
    ...mapGetters({
      user: 'auth/user',
      timezone: 'timezone/timezone'
    })
  },
  watch: {
    battle () {
      this.battleUpdate.name = this.battle.name
      this.battleUpdate.description = this.battle.description
      this.battleUpdate.start_time = this.battle.start_time
      this.battleUpdate.end_time = this.battle.end_time
    }
  },
  methods: {
    async editBattle () {
      var data = {
        name: this.battleUpdate.name,
        description: this.battleUpdate.description,
        voting_time: this.battleUpdate.voting_time
      }

      if (!this.hasBattleStarted) {
        data.start_time = this.battleUpdate.start_time
      }
      if (!this.hasBattleEnded) {
        data.end_time = this.battleUpdate.end_time
      }
      try {
        await this.battleUpdate.patch('/api/battles/' + this.battle.id, {
          data: data
        })
        await this.$store.dispatch('battles/fetchBattles')
        this.$noty.success('Battle updated successfully!')
        this.$modal.hide('edit-battle')
      } catch (e) {
        this.$noty.error(e.response.data.message)
      }
    },
    deleteBattle () {
      Swal.fire({
        type: 'warning',
        title: 'Delete Battle',
        text: 'Are you sure you want to delete this battle?',
        reverseButtons: true,
        confirmButtonText: 'Ok',
        cancelButtonText: 'Cancel'
      }).then(async (result) => {
        if (result.value) {
          await axios.delete('/api/battles/' + this.battle.id)
          this.$store.dispatch('battles/fetchBattles')
          this.$router.push({ name: 'battles' })
          this.$noty.success('Battle deleted successfully.')
        }
      })
    }
  }
}
</script>

<style scoped>
form {
  padding: 20px;
}

.form-timezone {
  position: absolute;
  bottom: 15px;
  right: 20px;
}
</style>
