<template>
  <card class="mb-4" style="min-width: 20vw" :title="battle.name">
    {{ battle.description }}
  </card>
</template>

<script>
export default {
  name: 'BattleCard',
  props: {
    battle: {
      type: Object,
      default: null
    }
  }
}
</script>
