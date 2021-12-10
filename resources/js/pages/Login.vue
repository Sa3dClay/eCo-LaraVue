<template>
    <div class="row justify-content-center">
        <div class="col-11 col-md-8 col-lg-5">
            <div class="card my-4">
                <div class="card-body">
                    <h2 class="card-title text-center">Login</h2>

                    <form>
                        <div class="mb-3">
                            <label for="email" class="form-label">
                                Email address
                            </label>

                            <input type="email" class="form-control" id="email" v-model="loginForm.email" required>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>

                            <input type="password" class="form-control" id="password" v-model="loginForm.password" required>
                        </div>

                        <button type="submit" class="btn btn-primary" @click.prevent="login">
                            Submit
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data: () => ({
            loginForm: {
                email: '',
                password: ''
            }
        }),
        methods: {
            login(
                event,
                email = this.loginForm.email,
                password = this.loginForm.password
            ) {
                console.log("Login:", email, password)

                if(this.validateForm()) {
                    // axios
                    axios.post('api/auth/login', {
                        email: email,
                        password: password
                    })
                        .then(res => {
                            console.log(res)
                            let user = res.data.data
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
                            console.log(axios.defaults.headers.common)

                            // reset form
                            this.resetForm()

                            // router
                            this.$router.push('/')
                        })
                        .catch(err => {
                            console.log(err)

                            this.$swal({
                                icon: 'error',
                                title: 'Oops...',
                                text: err,
                            })
                        })
                } else {
                    this.$swal({
                        icon: 'info',
                        title: 'Data missing!'
                    })
                }
            },
            validateForm() {
                if(
                    this.loginForm.email &&
                    this.loginForm.password
                ) {
                    return true;
                } else {
                    return false;
                }
            },
            resetForm() {
                this.loginForm.email = ''
                this.loginForm.password = ''
            }
        }
    }
</script>
