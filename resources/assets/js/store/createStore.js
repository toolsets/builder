import Vuex from 'vuex'

export default (initialState = {}, getters={}, mutations={}, actions={}) => {

    const store = new Vuex.Store({
        state: initialState,
        getters,
        mutations,
        actions
    })

    return store
}