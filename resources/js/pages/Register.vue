<template>
    <div class="row justify-content-center">
        <div class="col-11 col-md-8 col-lg-5">
            <div class="card my-4">
                <div class="card-body">
                    <h2 class="card-title text-center">Register</h2>

                    <form @submit.prevent="register" @keydown="form.onKeydown($event)">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>

                            <input
                                id="name"
                                name="name"
                                type="text"
                                class="form-control"
                                v-model="form.name"
                                required
                            >

                            <div
                                v-if="form.errors.has('name')"
                                v-html="form.errors.get('name')"
                                class="bg-danger rounded text-white text-center p-2 m-2"
                            />
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email address</label>

                            <input
                                id="email"
                                name="email"
                                type="email"
                                class="form-control"
                                aria-describedby="emailHelp"
                                v-model="form.email"
                                required
                            >
                            
                            <div id="emailHelp" class="form-text">
                                We'll never share your email with anyone else.
                            </div>

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
                            <label for="passwordConfirm" class="form-label">Confirm Password</label>

                            <input
                                type="password"
                                class="form-control"
                                id="passwordConfirm"
                                v-model="form.passwordConfirm"
                                minlength="8"
                                required
                            >
                        </div>

                        <button type="submit" class="btn btn-primary" :disabled="form.busy">
                            Register
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
            name: '',
            email: '',
            password: '',
            passwordConfirm: ''
        })
    }),
    methods: {
        async register() {
            // check for password confirmation
            if(this.form.passwordConfirm !== this.form.password) {
                this.$swal({
                    icon: 'info',
                    title: 'Password must match!'
                })

                return false
            }

            let res = await this.form.post('/api/auth/register')

            console.log(res)

            // success
            if(
                res.status == '201' ||
                res.status == '200'
            ) {
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
                this.$router.push('/email/verify')
            }
        }
    }
}
</script>
