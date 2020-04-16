<template>
  <div class="battle-card hvr-float">
    <router-link tag="div" :to="'/battles/' + battle.id">
      <card class="mb-4" style="min-width: 20vw" :title="battle.name">
        {{ battle.description }}
      </card>
    </router-link>
  </div>
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

<style>
  .battle-card {
    cursor: pointer;
  }
</style>
