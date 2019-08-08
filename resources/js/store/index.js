import Vue from 'vue'
import Vuex from 'vuex'

import messages from './messages'
import loading from './loading'

const state = {
}

const getters = {
}

const mutations = {
}

const actions = {
}

export default new Vuex.Store({
    state: state,
    getters: getters,
    mutations: mutations,
    modules: {
        loading: loading,
        messages: messages,
        panels: panels,
        users: users,
        workingGroups: workingGroups,
    },
    actions: actions,
    strict: debug,
})
