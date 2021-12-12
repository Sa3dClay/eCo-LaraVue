<template>
    <div class="my-4">
        <h1 class="text-center" v-if="loggedIn">
            Hello {{ user.name }} !
        </h1>
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
