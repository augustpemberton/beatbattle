<template>
  <div class="card-deck">
    <battle-card v-for="battle in battles" :key="battle.id" :battle="battle" />
  </div>
</template>

<script>
export default {
  name: 'BattleList',
  metaInfo () {
    return {
      title: 'Battles'
    }
  },
  props: {
    battles: {
      type: Array,
      default: () => []
    }
  }
}
</script>
