<template>
    <div class="row justify-content-center">
        <div class="col-11 col-md-8 col-lg-5">
            <div class="card my-4">
                <div class="card-body">
                    <h2 class="card-title text-center">Login</h2>

                    <form @submit.prevent="login" @keydown="form.onKeydown($event)">
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

                        <button type="submit" class="btn btn-primary" :disabled="form.busy">
                            Login
                        </button>
                    </form>

                    <a
                        class="btn d-block"
                        data-bs-toggle="modal"
                        data-bs-target="#forgotPasswordModel"
                    >
                        Forget your password?
                    </a>
                </div>
            </div>

            <!-- str Modal -->
            <div
                class="modal fade"
                id="forgotPasswordModel"
                tabindex="-1"
                aria-hidden="true"
            >
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button
                                type="button"
                                class="btn-close"
                                aria-label="Close"
                                data-bs-dismiss="modal"
                            ></button>
                        </div>
                        
                        <div class="modal-body">
                            <div class="card my-4">
                                <div class="card-body">
                                    <form
                                        @submit.prevent="forgotPassword"
                                        @keydown="form.onKeydown($event)"
                                    >
                                        <div class="mb-3">
                                            <label for="emailForgot" class="form-label">Enter your email address</label>

                                            <input
                                                name="email"
                                                type="email"
                                                id="emailForgot"
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

                                        <button type="submit" class="btn btn-primary" :disabled="form.busy">
                                            Send Reset Link
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end Modal -->

        </div>
    </div>
</template>

<script>
import Form from 'vform'

export default {
    data: () => ({
        form: new Form({
            email: '',
            password: ''
        })
    }),
    methods: {
        // Login
        async login() {
            this.form.post('/api/auth/login')
                .then(res => {
                    // console.log(res)

                    let user = res.data.user
                    let token = res.data.token

                    // store
                    this.$store.commit('login', {
                        user: user,
                        token: token
                    })

                    // session
                    this.$session.start()
                    this.$session.set('user', user)
                    this.$session.set('token', token)

                    // axios auth headers
                    axios.defaults.headers.common['Authorization'] = 'Bearer ' + token

                    // reset form
                    this.form.reset()

                    // router
                    this.$router.push('/')
                })
                .catch(err => {
                    console.log(err.response)

                    this.$swal({
                        icon: 'error',
                        title: 'Wrong email or password!',
                        showConfirmButton: true
                    })
                })
        },
        // Forgot Password
        async forgotPassword() {
            await this.form.post('api/auth/forgot-password')
                .then(res => {
                    // console.log(res)

                    this.$swal({
                        icon: 'success',
                        title: res.data.status,
                        text: 'you may find email in spam',
                        showConfirmButton: false,
                        timer: 1500
                    })

                    this.form.reset()
                })
                .catch(err => {
                    console.log(err.response)
                })
        }
    }
}
</script>
