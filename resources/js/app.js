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
import Cart from './pages/Cart.vue'
import Login from './pages/Login.vue'
import Register from './pages/Register.vue'
import ResetPassword from './pages/password/reset.vue'
import VerifyEmail from './pages/EmailVerification/VerifyEmail'
import EmailVerified from './pages/EmailVerification/EmailVerified'
import Products from './pages/products/index.vue'
import ProductCreate from './pages/products/create.vue'
import ProductUpdate from './pages/products/update.vue'
import NotFound from './pages/NotFound.vue'

// Routes
const routes = [
    { path: '/', name: 'Home', component: Home },
    { path: '/login', name: 'Login', component: Login },
    { path: '/register', name: 'Register', component: Register },
    { path: '/email/verify', name: 'VerifyEmail', component: VerifyEmail },
    { path: '/email/verified', name: 'EmailVerified', component: EmailVerified },
    { path: '/password/reset', name: 'ResetPassword', component: ResetPassword },
    { path: '/cart', name: 'Cart', component: Cart },
    { path: '/store', name: 'Products', component: Products },
    { path: '/products/create', name: 'ProductCreate', component: ProductCreate },
    { path: '/products/edit/:id', name: 'ProductUpdate', component: ProductUpdate },
    { path: '*', name: 'NotFound', component: NotFound }
]
const router = new VueRouter({
    routes
})

// validate routes
const authRoutes = ['Login', 'Register', 'ResetPassword']
const verifyRoutes = ['VerifyEmail', 'EmailVerified']

// Route guards
router.beforeEach((to, from, next) => {
    // Guest
    if(!authRoutes.includes(to.name) && !localStorage.getItem('vue-session-key')) next ({ name: 'Login' })
    next()
    
    // Authenticated User
    if(authRoutes.includes(to.name) && localStorage.getItem('vue-session-key')) next ({ name: 'Home' })
    else next()

    // get session as json
    if(localStorage.getItem('vue-session-key')) {
        var session = JSON.parse(localStorage.getItem('vue-session-key'))
    }

    // Unverified User
    if(!verifyRoutes.includes(to.name) && localStorage.getItem('vue-session-key') && !session.verified) next ({ name: 'VerifyEmail' })
    else next()
    
    // Verified User
    if(verifyRoutes.includes(to.name) && localStorage.getItem('vue-session-key') && session.verified) next ({ name: 'Home' })
    else next()
})

// Store
const store = new Vuex.Store({
    state: {
        user: null,
        token: null,
        loggedIn: false,
        cartCounter: 0
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
        },
        getCartCounter(state) {
            return state.cartCounter
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
        },
        increaseCartCounter(state) {
            state.cartCounter += 1
        },
        resetCartCounter(state) {
            state.cartCounter = 0
        }
    }
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
