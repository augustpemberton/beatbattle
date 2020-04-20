<template>
  <modal height="auto" name="create-battle">
    <form @submit.prevent="createBattle" @keydown="battle.onKeydown($event)">
      <!-- Name -->
      <div class="form-group">
        <label for="name-input">Name</label>
        <input
          id="name-input"
          v-model="battle.name"
          :class="{ 'is-invalid': battle.errors.has('name') }"
          class="form-control"
          type="text"
        >
        <has-error :form="battle" field="name" />
      </div>

      <!-- Description -->
      <div class="form-group">
        <label for="description-input">Description</label>
        <textarea
          id="description-input"
          v-model="battle.description"
          :class="{ 'is-invalid': battle.errors.has('description') }"
          class="form-control"
          rows="3"
        />
        <has-error :form="battle" field="description" />
      </div>

      <!-- Sample -->
      <div class="form-group">
        <label for="sample-upload">Samples (max 10)</label>
        <sample-upload
          id="sample-upload"
          v-model="battle.samples"
          :max-files="10"
          :class="{ 'is-invalid': battle.errors.has('samples') }"
        />
        <has-error :form="battle" field="samples" />
      </div>

      <!-- Start Time -->
      <div class="form-group">
        <label for="start-time-input">Start Time</label>
        <datetime
          id="start-time-input"
          v-model="battle.start_time"
          :min-datetime="$moment().add(1, 'minutes').toISOString()"
          :class="{ 'is-invalid': battle.errors.has('start_time') }"
          type="datetime"
        />
        <has-error :form="battle" field="start_time" />
      </div>

      <!-- End Time -->
      <div class="form-group">
        <label for="end-time-input">End Time</label>
        <datetime
          id="end-time-input"
          v-model="battle.end_time"
          :min-datetime="$moment(battle.start_time).add(10, 'minutes').toISOString()"
          :class="{ 'is-invalid': battle.errors.has('end_time') }"
          type="datetime"
        />
        <has-error :form="battle" field="end_time" />
      </div>

      <!-- Voting Time -->
      <div class="form-group">
        <label for="voting-time-input">Voting Period</label>
        <select
          id="voting-time-input"
          v-model="voting_period"
          class="form-control"
          :class="{ 'is-invalid': battle.errors.has('voting_time') }"
          @change="changeVotingTime"
        >
          <option value="5">
            5 mins
          </option>
          <option value="10">
            10 mins
          </option>
          <option value="30">
            30 mins
          </option>
          <option value="60">
            1 hour
          </option>
          <option value="1440">
            1 day
          </option>
          <option value="10080">
            1 week
          </option>
          <option value="custom">
            Custom time
          </option>
        </select>
        <has-error :form="battle" field="voting_time" />
      </div>

      <div
        v-show="voting_period === 'custom'"
        class="form-group"
      >
        <datetime
          id="custom-voting-time-input"
          ref="custom_voting_time"
          v-model="custom_voting_time"
          :min-datetime="$moment(battle.end_time).add(1, 'minutes').toISOString()"
          type="datetime"
        />
      </div>

      <!-- Submit -->
      <v-button :loading="battle.busy">
        Create Battle
      </v-button>
      <!-- Timezone display -->
      <div v-if="user != null" class="form-timezone">
        <small id="timezone-info" class="form-text text-muted">Timezone: {{ user.timezone }}</small>
      </div>
    </form>
  </modal>
</template>
<script>
import { mapGetters } from 'vuex'
import Form from 'vform'
import { objectToFormData } from 'object-to-formdata'

export default {
  name: 'CreateBattle',
  data () {
    return {
      battle: new Form({
        name: '',
        description: '',
        samples: [],
        start_time: null,
        end_time: null
      }),
      voting_period: '30',
      custom_voting_time: null
    }
  },
  computed: {
    voting_time () {
      if (this.voting_period === 'custom') {
        return this.custom_voting_time
      } else {
        return this.$moment(this.battle.end_time)
          .add(parseInt(this.voting_period), 'minutes').toISOString()
      }
    },
    ...mapGetters({
      user: 'auth/user'
    })
  },
  methods: {
    async createBattle () {
      this.battle.voting_time = this.voting_time
      await this.battle.submit('post', '/api/battles', {
        transformRequest: [function (data, headers) {
          return objectToFormData(data)
        }]
      })
      await this.$store.dispatch('battles/fetchBattles')

      this.$modal.hide('create-battle')
      this.$noty.success('Battle created successfully!')
    },
    changeVotingTime ($event) {
      if (this.voting_period === 'custom') {
        this.$refs.custom_voting_time.open($event)
      }
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
