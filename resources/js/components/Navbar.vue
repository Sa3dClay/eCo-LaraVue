<template>
    <div>
        <nav class="navbar navbar-expand-md navbar-dark bg-dark">
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
                            <router-link class="nav-link" to="/products">Products</router-link>
                        </li>

                        <li class="nav-item">
                            <a type="button" class="nav-link" @click.prevent="logout">Logout</a>
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
</style>

<script>
import { mapGetters } from 'vuex'

export default {
    data: () => ({
        logo: 'eco.png'
    }),
    methods: {
        logout() {
            // this.$session.destroy()

            axios.post('api/auth/logout')
                .then(res => {
                    // console.log(res)

                    localStorage.clear()

                    this.$store.commit('logout')
                    this.$router.push('/login')
                })
                .catch(err => {
                    console.log(err)
                })
        }
    },
    computed: {
        ...mapGetters ({
            loggedIn: 'isLoggedIn',
            token: 'getToken'
        })
    }
}
</script>
