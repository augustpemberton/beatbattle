<template>
  <li class="media my-3">
    <img v-if="!isUserMessage" :src="message.user.photo_url" class="avatar" :class="'mr-3'">
    <div class="media-body" :class="{'text-right': isUserMessage}">
      <h5 class="message-header mt-0 mb-1" style="font-size: 1em">
        {{ message.user.name }} <small> {{ message.created_at | moment('h:mm a') }} </small>
      </h5>
      {{ message.message }}
    </div>
    <img v-if="isUserMessage" :src="user.photo_url" class="avatar" :class="'ml-3'">
  </li>
</template>

<script>
import { mapGetters } from 'vuex'
export default {
  name: 'Message',
  props: {
    message: {
      type: Object,
      default: null
    }
  },
  computed: {
    isUserMessage () {
      if (!this.message.user || !this.user) return false
      return (this.message.user.id === this.user.id)
    },
    ...mapGetters({
      user: 'auth/user'
    })
  }
}
</script>
<style lang="scss" scoped>
.avatar {
  width: 64px;
  height: 64px;
}
</style>
