<template>
    <div>
        <nav class="navbar navbar-expand-md navbar-dark bg-dark">
            <div class="container-fluid">
                <a class="navbar-brand">BlueDev</a>

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
                            <router-link class="nav-link" to="/">Home</router-link>
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

<script>
export default {
    data: () => ({
        loggedIn: false
    }),
    methods: {
        isLoggedIn() {
            return this.$session.exists()
        },
        logout() {
            this.$session.destroy()
            this.$router.push('/login')
            this.loggedIn = false
        }
    },
    created() {
        this.loggedIn = this.isLoggedIn
    }
}
</script>
