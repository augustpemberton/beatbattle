<template>
  <card :title="$t('home')">
    {{ $t('you_are_logged_in') }}

    <button
      class="btn btn-default"
      @click="createBattle()"
    >
      Create Battle
    </button>
  </card>
</template>

<script>
import axios from 'axios'
export default {
  middleware: 'auth',

  metaInfo () {
    return { title: this.$t('home') }
  },

  methods: {
    createBattle () {
      console.log('Create Battle')
      var battle = {
        'name': 'Battle1',
        'description': '',
        'sample_id': 1,
        'start_time': new Date(),
        'end_time': new Date()
      }
      axios.post('/api/battles', battle)
        .then((response) => {
          console.log(response)
        })
    }
  }
}
</script>
