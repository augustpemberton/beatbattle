import moment from 'moment-timezone'
import * as types from '../mutation-types'

// state
export const state = {
  timezone: null
}

// getters
export const getters = {
  timezone: state => state.timezone
}

export const mutations = {
  [types.UPDATE_TIMEZONE] (state, { timezone }) {
    state.timezone = timezone
  }
}

export const actions = {
  updateTimezone ({ commit }) {
    commit(types.UPDATE_TIMEZONE, { timezone: moment.tz.guess() })
  }
}
