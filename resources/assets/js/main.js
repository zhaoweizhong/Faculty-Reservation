
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import App from './App'
import router from './router/lazy'
import 'ant-design-vue/dist/antd.css'
import Viser from 'viser-vue'
import axios from 'axios'
import message from 'ant-design-vue/es/message'
import './mock'
import store from './store'
import { getCookie } from 'tiny-cookie'

Vue.prototype.$axios = axios
Vue.prototype.router = router
Vue.prototype.$message = message
Vue.prototype.$store = store
Vue.config.productionTip = false
Vue.use(Viser)

axios.interceptors.response.use(function(response) {
        return response
}, function(error) {
        const originalRequest = error.config
    if (error.response.status === 401 && !originalRequest._retry) {
        originalRequest._retry = true
                axios.defaults.headers.common['Authorization'] = 'Bearer ' + getCookie('token')
                return axios.put('/api/authorizations/current')
        .then((res) => {
            //console.log('Data: ' + JSON.stringify(data))
            store.commit('account/refreshToken', res.data.access_token)
            // retry request
            originalRequest.headers.Authorization = 'Bearer ' + res.data.access_token
            return axios(originalRequest)
        })
    }
        return Promise.reject(error)
})

/* eslint-disable no-new */
new Vue({
    el: '#app',
    router,
    store,
    components: { App },
    template: '<App/>'
})
