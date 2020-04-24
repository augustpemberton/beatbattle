<template>
  <div class="chat">
    <ul id="messages" v-chat-scroll="{always: false, smooth: true, notSmoothOnInit: true}" class="list-unstyled">
      <message v-for="(message, index) in messages" :key="index" :message="message" />
    </ul>
    <footer>
      <form class="p-0 input-group mb-3" @submit.prevent="sendMessage">
        <input id="chat-input" v-model="messageInput" type="text" class="form-control" placeholder="Enter message here">
        <div class="input-group-append">
          <button type="submit" class="btn btn-outline-secondary">
            Send
          </button>
        </div>
      </form>
    </footer>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  name: 'Chat',
  data () {
    return {
      messages: [],
      messageInput: '',
      scrolled: false
    }
  },
  mounted () {
    this.updateChat()
    window.echo.channel('chat')
      .listen('NewMessage', (e) => {
        console.log(e)
        this.messages.push(e.message)
      })
  },
  methods: {
    async updateChat () {
      try {
        var messages = await axios.get('/api/chat')
        this.messages = messages.data.data
      } catch (e) {
        console.log(e)
        this.$noty.error('There was an error updating the chat.')
      }
    },
    async sendMessage () {
      try {
        await axios.post('/api/chat', {
          message: this.messageInput
        })
        this.messageInput = ''
      } catch (e) {
        console.log(e)
        this.$noty.error('There was an error sending your message')
      }
    }
  }
}
</script>
<style lang="scss" scoped>
.chat {
  height: calc(100vh - 150px);
}
#messages {
  height: 100%;
  overflow-y: scroll;
  padding: 15px;
}

.chat footer {
  position: absolute;
  bottom: -50px;
  height: 50px;
  width: 100%;
  padding: 15px;
}

</style>
