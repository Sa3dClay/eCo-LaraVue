require('./bootstrap');

import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)

import App from './App.vue'
import Home from './pages/Home.vue'
import Login from './pages/Login.vue'
import Register from './pages/Register.vue'

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
    router,
    components: {
        App,
        Home,
        Login,
        Register
    }
}).$mount('#app')
