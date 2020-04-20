import Echo from 'laravel-echo'
import Pusher from 'pusher-js'

window.pusher = Pusher
window.echo = new Echo({
  broadcaster: 'pusher',
  key: 'beatbattle',
  wsHost: window.location.hostname,
  wsPort: 6001,
  disableStats: true
})
