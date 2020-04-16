<template>
  <div>
    <create-battle />
    <button class="btn btn-info" @click="$modal.show('create-battle')">
      Create Battle
    </button>
    <battle-list />
  </div>
</template>

<script>
export default {
  mounted () {
    this.$store.dispatch('battles/fetchBattles')
  }
}
</script>
