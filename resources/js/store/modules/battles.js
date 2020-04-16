import axios from 'axios'
import * as types from '../mutation-types'

// state
export const state = {
  battles: null
}

// getters
export const getters = {
  battles: state => state.battles
}

// mutations
export const mutations = {
  [types.FETCH_BATTLES_SUCCESS] (state, { battles }) {
    state.battles = battles
  },
  [types.FETCH_BATTLES_FAILURE] (state) {
    state.battles = null
  }
}

export const actions = {
  async fetchBattles ({ commit }) {
    try {
      const { data } = await axios.get('/api/battles')
      commit(types.FETCH_BATTLES_SUCCESS, { battles: data.data })
    } catch (e) {
      commit(types.FETCH_BATTLES_FAILURE)
    }
  }
}
