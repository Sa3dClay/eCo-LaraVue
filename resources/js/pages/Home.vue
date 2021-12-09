<template>
    <div class="my-4">
        <h1 class="text-center" v-if="isLoggedIn()">
            Welcome {{ getUser().name }}
        </h1>
    </div>
</template>

<script>
    export default {
        methods: {
            isLoggedIn() {
                return this.$session.exists()
            },
            getUser() {
                return this.$session.get('user')
            }
        }
    }
</script>
