import Vue from 'vue'
import axios from 'axios'
import * as types from '../mutation-types'

// state
export const state = {
  battles: null,
  entries: {}
}

// getters
export const getters = {
  battles: state => state.battles,
  entries: state => battleid => state.entries[battleid]
}

// mutations
export const mutations = {
  [types.FETCH_BATTLES_SUCCESS] (state, { battles }) {
    state.battles = battles
  },
  [types.FETCH_BATTLES_FAILURE] (state) {
    state.battles = null
  },
  [types.FETCH_ENTRIES_SUCCESS] (state, { id, entries }) {
    console.log(id)
    console.log(entries)
    Vue.set(state.entries, id, entries)
  },
  [types.FETCH_ENTRIES_FAILURE] (state, { id }) {
    Vue.set(state.entries, id, null)
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
  },
  async fetchEntries ({ commit }, id) {
    try {
      const { data } = await axios.get('/api/entries/' + id)
      commit(types.FETCH_ENTRIES_SUCCESS, { id: id, entries: data.data })
    } catch (e) {
      commit(types.FETCH_BATTLES_FAILURE, { id: id })
    }
  }
}
