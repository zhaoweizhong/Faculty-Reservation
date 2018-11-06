import {
    setCookie,
    removeCookie
} from 'tiny-cookie'
import axios from 'axios'

export default {
    namespaced: true,
    state: {
        token: '',
        user: {}
    },
    mutations: {
        login(state, token) {
            state.token = token
            setCookie('token', token)
            axios.defaults.headers.common['Authorization'] = 'Bearer ' + token
            axios.get('/api/user')
                .then(function (response) {
                    state.user = response.data
                });
        },
        logout(state) {
            state.token = ''
            removeCookie('token')
        },
        // 用户刷新 token 成功，使用新的 token 替换掉本地的token
        refreshToken(state, token) {
            state.token = token
            setCookie('token', token)
            axios.defaults.headers.common['Authorization'] = 'Bearer ' + token
        },
    }
}
