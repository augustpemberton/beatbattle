<template>
  <div class="container">
    <ul v-if="samples.length > 0">
      <li
        v-for="sample in samples"
        :key="sample.id"
      >
        {{ sample.filename }}
      </li>
    </ul>
    <span v-else>
      You have no samples yet.
    </span>
  </div>
</template>

<script>
import axios from 'axios'
export default {
  data () {
    return {
      samples: []
    }
  },

  mounted () {
    this.updateSamples()
  },

  methods: {
    updateSamples () {
      axios.get('/api/samples')
        .then((response) => {
          this.samples = response.data.data
        })
    }
  }
}
</script>
