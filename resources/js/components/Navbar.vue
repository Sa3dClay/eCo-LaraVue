<template>
    <div>
        <nav class="navbar fixed-top navbar-expand-md navbar-dark bg-dark">
            <div class="container-fluid">
                <router-link class="navbar-brand" to="/">
                    <img :src="'../img/logo/'+logo" alt="eco" width="40">
                </router-link>

                <button
                    type="button"
                    class="navbar-toggler"
                    data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent"
                    aria-expanded="false"
                    aria-label="Toggle navigation"
                >
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto" v-if="loggedIn">
                        <li class="nav-item">
                            <router-link class="nav-link" to="/store">Store</router-link>
                        </li>
                        
                        <li class="nav-item px-2" v-if="user.role == 0">
                            <router-link class="nav-link" to="/products/create">
                                <i class="bi bi-bag-plus-fill"></i>
                            </router-link>
                        </li>
                        
                        <li class="nav-item px-2" v-if="user.role == 1">
                            <router-link class="nav-link position-relative" to="/cart">
                                <i class="bi bi-cart3"></i>

                                <span
                                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                                    v-if="cartCounter > 0"
                                >
                                    <span>{{ cartCounter }}</span>

                                    <span class="visually-hidden">unread messages</span>
                                </span>
                            </router-link>
                        </li>

                        <li class="nav-item px-2">
                            <a type="button" class="nav-link" @click.prevent="logout">
                                <i class="bi bi-box-arrow-left"></i>
                            </a>
                        </li>
                    </ul>

                    <ul class="navbar-nav ms-auto" v-else>
                        <li class="nav-item">
                            <router-link class="nav-link" to="/login">Login</router-link>
                        </li>

                        <li class="nav-item">
                            <router-link class="nav-link" to="/register">Register</router-link>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
</template>

<style lang="scss" scoped>
    .navbar-brand {
        img {
            background-color: #fff;
        }
    }
    .nav-link {
        text-align: center;
    }
</style>

<script>
import { mapGetters } from 'vuex'

export default {
    data: () => ({
        logo: 'eco.png'
    }),
    methods: {
        logout() {
            this.$session.destroy()
            localStorage.clear()

            this.$store.commit('logout')
            this.$router.push('/login')
        }
    },
    computed: {
        ...mapGetters ({
            loggedIn: 'isLoggedIn',
            token: 'getToken',
            user: 'getUser',
            cartCounter: 'getCartCounter'
        })
    }
}
</script>
