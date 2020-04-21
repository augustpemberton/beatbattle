import Vue from 'vue'
import axios from 'axios'
import * as types from '../mutation-types'

// state
export const state = {
  battles: [],
  entries: {},
  votes: {}
}

// getters
export const getters = {
  battles: state => state.battles,
  entries: state => battleid => state.entries[battleid],
  votes: state => battleid => state.votes[battleid]
}

// mutations
export const mutations = {
  [types.FETCH_BATTLES_SUCCESS] (state, { battles }) {
    state.battles = battles
  },
  [types.FETCH_BATTLES_FAILURE] (state) {
    state.battles = null
  },
  [types.FETCH_BATTLE_SUCCESS] (state, { battle }) {
    var battleIndex = state.battles.findIndex(b => b.id === battle.id)
    Vue.set(state.battles, battleIndex, battle)
  },
  [types.FETCH_BATTLE_FAILURE] (state) {
    // if we cant fetch a specific battle, do nothing
  },
  [types.FETCH_ENTRIES_SUCCESS] (state, { battleid, entries }) {
    Vue.set(state.entries, battleid, entries)
  },
  [types.FETCH_ENTRIES_FAILURE] (state, { battleid }) {
    Vue.set(state.entries, battleid, null)
  },
  [types.FETCH_VOTES_SUCCESS] (state, { battleid, votes }) {
    Vue.set(state.votes, battleid, votes)
  },
  [types.FETCH_VOTES_FAILURE] (state, { battleid }) {
    Vue.set(state.votes, battleid, null)
  },
  [types.DELETE_ENTRY] (state, { userid, battleid }) {
    state.entries[battleid] = state.entries[battleid].filter(e => {
      return e.user.id !== userid
    })
  },
  [types.DELETE_BATTLE] (state, { battleid }) {
    state.battles = state.battles.filter(b => {
      return b.id !== battleid
    })
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
  async fetchBattle ({ commit }, battleid) {
    try {
      const { data } = await axios.get('/api/battles/' + battleid)
      commit(types.FETCH_BATTLE_SUCCESS, { battle: data.data })
    } catch (e) {
      commit(types.FETCH_BATTLE_FAILURE)
      // if we couldn't fetch a specific battle, try updating them all
      this.dispatch('battles/fetchBattles')
    }
  },
  async fetchEntries ({ commit }, battleid) {
    try {
      const { data } = await axios.get('/api/entries/' + battleid)
      commit(types.FETCH_ENTRIES_SUCCESS, { battleid: battleid, entries: data.data })
    } catch (e) {
      commit(types.FETCH_ENTRIES_FAILURE, { battleid: battleid })
    }
  },
  async fetchVotes ({ commit }, battleid) {
    try {
      const { data } = await axios.get('/api/votes/' + battleid)
      commit(types.FETCH_VOTES_SUCCESS, { battleid: battleid, votes: data.data })
    } catch (e) {
      commit(types.FETCH_VOTES_FAILURE, { battleid: battleid })
    }
  }
}
