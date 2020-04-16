<template>
  <div class="card-deck">
    <battle-card v-for="battle in battles" :key="battle.id" :battle="battle" />
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
export default {
  name: 'BattleList',
  metaInfo () {
    return {
      title: 'Battles'
    }
  },
  computed: mapGetters({
    battles: 'battles/battles'
  })
}
</script>
