const state = {
    requestCount: 0,
    apiRequestCounts: {
        omim: 0,
        mondo: 0,
        pubmed: 0,
    }
}

const getters = {
    loading(state) {
        console.log(state.requestCount);
        return state.requestCount > 0
    },
    apiLoading(state, apiKey) {
        if (typeof apiKey == 'object') {
            return false;
        }
        if (Object.keys(state.apiRequestCounts).indexOf(apiKey) < 0) {
            throw new Error(apiKey + ' is not a valid key for apiRequestCounts.')
        }
        return state.apiRequestCounts[apiKey] > 0
    },
    omimLoading(state) {
        return state.apiRequestCounts['omim'] > 0
    }
}

const mutations = {
    addRequest(state) {
        state.requestCount++;
    },
    removeRequest(state) {
        state.requestCount--;
    },
    addApiRequest(state, apiKey) {
        if (typeof apiKey == 'object') {
            return false;
        }
        if (Object.keys(state.apiRequestCounts).indexOf(apiKey) < 0) {
            throw new Error(apiKey + ' is not a valid key for apiRequestCounts.')
        }
        state.apiRequestCounts[apiKey]++
    },
    removeApiRequest(state, apiKey) {
        if (typeof apiKey == 'object') {
            return false;
        }
        if (Object.keys(state.apiRequestCounts).indexOf(apiKey) < 0) {
            throw new Error(apiKey + ' is not a valid key for apiRequestCounts.')
        }
        state.apiRequestCounts[apiKey]--
    }
}
