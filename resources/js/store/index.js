import Vue from 'vue';
import Vuex from 'vuex';

import messages from './messages';
import loading from './loading';
import getCurrentUser from '../resources/users/get_current_user';
import User from '../user.js';
import configs from './../configs.json';

Vue.use(Vuex)

const debug = process.env.NODE_ENV !== 'production'

const state = {
    configs: configs,
    user: new User(),
    requestCount: 0,
}

const getters = {
    getUser: state => state.user,
    loading (state) {
        return state.requestCount > 0
    },
}

const mutations = {
    setUser (state, user) {
        state.user = new User(user);
    },
    addRequest(state) {
        state.requestCount++;
    },
    removeRequest(state) {
        state.requestCount--;
    },    
}

const actions = {
    async fetchUser ({commit}) {
        const user = await getCurrentUser();
        if (user) {
            commit('setUser', user);
        }
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
