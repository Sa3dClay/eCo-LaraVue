<template>
    <div class="row justify-content-center">
        <div class="col-11 col-md-8 col-lg-5">
            <div class="card my-4">
                <div class="card-body">
                    <h2 class="card-title text-center">Reset Password</h2>

                    <form @submit.prevent="resetPassword" @keydown="form.onKeydown($event)">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email address</label>

                            <input
                                id="email"
                                name="email"
                                type="email"
                                class="form-control"
                                v-model="form.email"
                                required
                            >

                            <div
                                v-if="form.errors.has('email')"
                                v-html="form.errors.get('email')"
                                class="bg-danger rounded text-white text-center p-2 m-2"
                            />
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>

                            <input
                                id="password"
                                name="password"
                                type="password"
                                class="form-control"
                                v-model="form.password"
                                minlength="8"
                                required
                            >

                            <div
                                v-if="form.errors.has('password')"
                                v-html="form.errors.get('password')"
                                class="bg-danger rounded text-white text-center p-2 m-2"
                            />
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>

                            <input
                                id="password_confirmation"
                                name="password_confirmation"
                                type="password"
                                class="form-control"
                                v-model="form.password_confirmation"
                                @keydown="notMatched = false"
                                minlength="8"
                                required
                            >

                            <div
                                v-if="notMatched"
                                v-html="'Password Confirm Must Match'"
                                class="bg-danger rounded text-white text-center p-2 m-2"
                            />
                        </div>

                        <button type="submit" class="btn btn-primary" :disabled="form.busy">
                            Update Password
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Form from 'vform'

export default {
    data: () => ({
        form: new Form({
            token: '',
            email: '',
            password: '',
            password_confirmation: ''
        }),
        notMatched: false
    }),
    methods: {
        async resetPassword() {
            if(!this.checkMatch()) {
                this.notMatched = true
            }

            await this.form.post('/api/auth/reset-password')
                .then(res => {
                    // console.log(res)

                    this.$swal({
                        icon: 'success',
                        title: res.data.status,
                        showConfirmButton: false,
                        timer: 1500
                    })

                    this.form.reset()

                    this.$router.push('/login')
                })
                .catch(err => {
                    console.log(err.response)
                })
        },
        checkMatch() {
            return this.form.password === this.form.password_confirmation
        }
    },
    created() {
        this.form.token = this.$route.query.token
    }
}
</script>
