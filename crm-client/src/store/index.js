import { createStore } from 'vuex';
import axios from 'axios';

export default createStore({

    state() {
        return {
            authenticated: false,
            user: null
        }
    },
    mutations: {
        SET_AUTHENTICATED (state, authenticated) {
            state.authenticated = authenticated;
        },
        SET_USER (state, user) {
            state.user = user
        }
    },
    actions: {
        // login
        async login ({ dispatch },credentials) {
            await axios.get('/sanctum/csrf-cookie');
            await axios.post('/login', credentials);
            dispatch('authenticate');
        },

        authenticate ({ commit }) {
           return axios.get('/api/user').then((response) => {

                commit('SET_AUTHENTICATED', true);
                commit('SET_USER', response.data);

            }).catch(() => {
                commit('SET_AUTHENTICATED', false);
                commit('SET_USER', null);
            });
        },
        async logout ({commit}) {
            try {
                await axios.post('/logout')
                commit('SET_AUTHENTICATED', false);
                commit('SET_USER', null);
            } catch (e) {
                console.log(e);
            }
        }
    },
    getters: {
        authenticated (state) {
            return state.authenticated;
        },
        user(state) {
            return state.user;
        }
    }
});