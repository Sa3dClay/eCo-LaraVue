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
            email: '',
            password: ''
        })
    }),
    methods: {
        async login() {
            this.form.post('/api/auth/login')
                .then(res => {
                    console.log(res)

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
        }
    }
}
</script>
