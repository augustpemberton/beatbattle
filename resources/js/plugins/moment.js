import Vue from 'vue'
import VueMoment from 'vue-moment'
import moment from 'moment-timezone'

moment.tz.guess()
Vue.use(VueMoment, { moment })
