
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
import PouchDB from 'pouchdb'

Vue.prototype.$axios = axios
Vue.prototype.router = router
Vue.prototype.$message = message
Vue.config.productionTip = false
Vue.use(Viser)

/* eslint-disable no-new */
new Vue({
    el: '#app',
    router,
    store,
    components: { App },
    template: '<App/>',
    mounted () {
        var db = new PouchDB('admindb')
        db.get('currUser').then(doc => {
            this.$store.commit('account/setuser', doc.user)
        })
    }
})
