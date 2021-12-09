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
    getters: {
        getUser(state) {
            return state.user
        },
        getToken(state) {
            return state.token
        },
        isLoggedIn(state) {
            return state.loggedIn
        }
    },
    mutations: {
        login (state, payload) {
            state.loggedIn = true
            state.user = payload.user
            state.token = payload.token
        },
        logout (state) {
            state.loggedIn = false
            state.user = null
            state.token = null
        }
    }
})

// Routes
const routes = [
    { path: '/', name: 'Home', component: Home },
    { path: '/login', name: 'Login', component: Login },
    { path: '/register', name: 'Register', component: Register }
]
const router = new VueRouter({
    routes
})

// validate routes
const authRoutes = ['Login', 'Register']

router.beforeEach((to, from, next) => {
    if(!authRoutes.includes(to.name) && !localStorage.getItem('vue-session-key')) next ({ name: 'Login' })
    next()
    
    if(authRoutes.includes(to.name) && localStorage.getItem('vue-session-key')) next ({ name: 'Home' })
})

// axios token
// let session = JSON.parse(localStorage.getItem('vue-session-key'))
// axios.defaults.headers.common['Authorization'] = 'Bearer ' + session.token

// vue instance
new Vue({
    store,
    router,
    components: {
        App,
        Home,
        Login,
        Register
    },
    created() {
        if(this.$session.exists()) {
            this.$store.commit('login', {
                user: this.$session.get('user'),
                token: this.$session.get('token')
            })
            // axios.defaults.headers.common['Authorization'] = 'Bearer ' + this.$session.get('token')
        }
    },
}).$mount('#app')
