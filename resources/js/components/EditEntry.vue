<template>
  <modal height="auto" name="edit-entry">
    <form @submit.prevent="updateEntry" @keydown="entryForm.onKeydown($event)">
      <!-- Sample -->
      <div class="form-group">
        <label for="sample-upload">Sample</label>
        <sample-upload
          id="sample-upload"
          v-model="entryForm.samples"
          :class="{ 'is-invalid': entryForm.errors.has('samples') }"
        />
        <has-error :form="entryForm" field="samples" />
      </div>

      <!-- Notes -->
      <div class="form-group">
        <label for="notes-input">Notes</label>
        <textarea
          id="notes-input"
          v-model="entryForm.notes"
          :class="{ 'is-invalid': entryForm.errors.has('notes') }"
          class="form-control"
          rows="3"
        />
        <has-error :form="entryForm" field="notes" />
      </div>

      <!-- Listenable Early -->
      <div class="form-check">
        <input
          id="listenable-early-input"
          v-model="entryForm.listenable_early"
          type="checkbox"
          :class="{ 'is-invalid': entryForm.errors.has('listenable_early') }"
          class="form-check-input"
        >
        <label for="listenable-early-input">Allow other users to listen to your submission before the game is finished?</label>
        <has-error :form="entryForm" field="listenable_early" />
      </div>

      <!-- Submit -->
      <v-button :loading="entryForm.busy">
        Update Entry
      </v-button>

      <button class="btn btn-warn" @click.prevent="deleteEntry">
        <fa icon="trash" fixed-width />
      </button>
    </form>
  </modal>
</template>
<script>
import Form from 'vform'
import { objectToFormData } from 'object-to-formdata'
import Swal from 'sweetalert2'
import axios from 'axios'

export default {
  name: 'EditEntry',
  props: {
    entry: {
      type: Object,
      default: null
    }
  },
  data () {
    return {
      entryForm: new Form({
        samples: [],
        notes: '',
        listenable_early: false
      })
    }
  },
  methods: {
    async updateEntry () {
      // Convert boolean into int
      this.entryForm.listenable_early = this.entryForm.listenable_early ? 1 : 0

      // laravel PATCH workaround
      // https://laracasts.com/discuss/channels/vue/vue-resource-patch-put-not-sending-the-data
      this.entryForm['_method'] = 'PATCH'

      await this.entryForm.submit('post', '/api/entries/' + this.entry.id, {
        transformRequest: [function (data, headers) {
          return objectToFormData(data)
        }]
      })
      await this.$store.dispatch('battles/fetchEntries', this.entry.battle_id)

      this.$modal.hide('edit-entry')
      this.$noty.success('Entry updated successfully!')
    },
    deleteEntry () {
      Swal.fire({
        type: 'warning',
        title: 'Delete Entry',
        text: 'Are you sure you want to delete your entry?',
        reverseButtons: true,
        confirmButtonText: 'Ok',
        cancelButtonText: 'Cancel'
      }).then(async (result) => {
        if (result.value) {
          await axios.delete('/api/entries/' + this.entry.id)
          this.$store.dispatch('battles/fetchEntries', this.$route.params.id)
          this.$noty.success('Entry deleted successfully.')
        }
      })
    },
  }
}
</script>

<style scoped>
form {
  padding: 20px;
}
</style>
