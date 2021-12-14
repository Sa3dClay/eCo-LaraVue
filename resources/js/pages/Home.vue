<template>
    <div class="row justify-content-center my-4">
        <div class="col-11 col-md-8 col-lg-5">
            <div class="card" v-if="loggedIn">
                <div class="card-body text-center">
                    <h2 class="card-title py-4">{{ 'Hello ' + user.name }}</h2>

                    <div v-if="user.role == 0">
                        <router-link
                            class="btn btn-primary"
                            to="/products/create"
                        >Add New Product</router-link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
    computed: {
        ...mapGetters ({
            loggedIn: 'isLoggedIn',
            user: 'getUser'
        })
    }
}
</script>
