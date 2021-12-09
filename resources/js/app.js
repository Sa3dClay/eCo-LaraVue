require('./bootstrap');

import Vue from 'vue'

import VueRouter from 'vue-router'
Vue.use(VueRouter)

import Vuex from 'vuex'
Vue.use(Vuex)

import VueSession from 'vue-session'
Vue.use(VueSession, {
    persist: true
})

import axios from 'axios';
import VueAxios from 'vue-axios'
Vue.use(VueAxios, axios)

import 'sweetalert2/dist/sweetalert2.min.css';
import VueSweetalert2 from 'vue-sweetalert2';
Vue.use(VueSweetalert2);

import App from './App.vue'
import Home from './pages/Home.vue'
import Login from './pages/Login.vue'
import Register from './pages/Register.vue'

// Store
const store = new Vuex.Store({
    state: {
        user: null,
        token: null,
        loggedIn: false
    },
    mutations: {
        login (state, payload) {
            state.loggedIn = true
            state.user = payload.user
            state.token = payload.token
        }
    }
})

// Routes
const routes = [
    { path: '/', component: Home },
    { path: '/login', component: Login },
    { path: '/register', component: Register }
]
const router = new VueRouter({
    routes
})

new Vue({
    store,
    router,
    components: {
        App,
        Home,
        Login,
        Register
    }
}).$mount('#app')
