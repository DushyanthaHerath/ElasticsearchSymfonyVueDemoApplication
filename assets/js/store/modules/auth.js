import axios from 'axios';
const state = {
    user: null,
    fields: null
};
const getters = {
    isAuthenticated: state => !!state.user,
    StateUser: state => state.user,
};
const actions = {
    async LogIn({commit}, User) {
        await axios.post('token', User)
        await commit('setUser', User.get('username'))
    },
    async LogOut({commit}){
        let user = null
        commit('LogOut', user)
    },
};
const mutations = {
    setUser(state, username){
        state.user = username;
    },
    LogOut(state){
        state.user = null
        state.posts = null
    },
};
export default {
    state,
    getters,
    actions,
    mutations
};