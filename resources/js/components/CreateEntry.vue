<template>
  <modal height="auto" name="create-entry">
    <form @submit.prevent="submitEntry" @keydown="entry.onKeydown($event)">
      <!-- Sample -->
      <div class="form-group">
        <label for="sample-upload">Sample</label>
        <sample-upload
          id="sample-upload"
          v-model="entry.samples"
          :class="{ 'is-invalid': entry.errors.has('samples') }"
        />
        <has-error :form="entry" field="samples" />
      </div>

      <!-- Notes -->
      <div class="form-group">
        <label for="notes-input">Notes</label>
        <textarea
          id="notes-input"
          v-model="entry.notes"
          :class="{ 'is-invalid': entry.errors.has('notes') }"
          class="form-control"
          rows="3"
        />
        <has-error :form="entry" field="notes" />
      </div>

      <!-- Listenable Early -->
      <div class="form-check">
        <input
          id="listenable-early-input"
          v-model="entry.listenable_early"
          type="checkbox"
          :class="{ 'is-invalid': entry.errors.has('listenable_early') }"
          class="form-check-input"
        >
        <label for="listenable-early-input">Allow other users to listen to your submission before the game is finished?</label>
        <has-error :form="entry" field="listenable_early" />
      </div>

      <!-- Submit -->
      <v-button :loading="entry.busy">
        Create Entry
      </v-button>
    </form>
  </modal>
</template>
<script>
import Form from 'vform'
import { objectToFormData } from 'object-to-formdata'

export default {
  name: 'CreateEntry',
  props: {
    battle: {
      type: Object,
      default: null
    }
  },
  data () {
    return {
      entry: new Form({
        samples: [],
        notes: '',
        listenable_early: false
      })
    }
  },
  methods: {
    async submitEntry () {
      // Set the entries battle ID
      this.entry.battle_id = this.battle.id
      
      // Convert boolean into int
      this.entry.listenable_early = this.entry.listenable_early ? 1 : 0

      await this.entry.submit('post', '/api/entries', {
        transformRequest: [function (data, headers) {
          console.log(objectToFormData(data))
          return objectToFormData(data)
        }]
      })
      await this.$store.dispatch('battles/fetchEntries', this.battle.id)

      this.$modal.hide('create-entry')
      this.$noty.success('Entry created successfully!')
    }
  }
}
</script>

<style scoped>
form {
  padding: 20px;
}
</style>
