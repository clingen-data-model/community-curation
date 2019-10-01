import Vue from 'vue';
import Vuex from 'vuex';

import messages from './messages';
import loading from './loading';
import getCurrentUser from '../resources/users/get_current_user';
import User from '../user.js';

Vue.use(Vuex)

const debug = process.env.NODE_ENV !== 'production'

const state = {
    user: new User()
}

const getters = {
}

const mutations = {
    setUser (state, user) {
        state.user = new User(user);
    }
}

const actions = {
    async fetchUser ({commit}) {
        commit('setUser', await getCurrentUser());
    }
}

export default new Vuex.Store({
    state: state,
    getters: getters,
    mutations: mutations,
    modules: {
        loading: loading,
        messages: messages,
        // panels: panels,
        // users: users,
        // workingGroups: workingGroups,
    },
    actions: actions,
    strict: debug,
})
