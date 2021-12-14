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
import NotFound from './pages/NotFound.vue'
import Products from './pages/products/index.vue'
import ProductCreate from './pages/products/create.vue'
import ProductUpdate from './pages/products/update.vue'

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
    { path: '/register', name: 'Register', component: Register },
    { path: '/store', name: 'Products', component: Products },
    { path: '/products/create', name: 'ProductCreate', component: ProductCreate },
    { path: '/products/edit/:id', name: 'ProductUpdate', component: ProductUpdate },
    { path: '*', name: 'NotFound', component: NotFound }
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

// get axios token from local storage session
// let session = JSON.parse(localStorage.getItem('vue-session-key'))
// axios.defaults.headers.common['Authorization'] = 'Bearer ' + session.token

// vue instance
new Vue({
    store,
    router,
    components: {
        App
    },
    created() {
        if(this.$session.exists()) {
            this.$store.commit('login', {
                user: this.$session.get('user'),
                token: this.$session.get('token')
            })
            axios.defaults.headers.common['Authorization'] = 'Bearer ' + this.$session.get('token')
        }
    },
}).$mount('#app')
