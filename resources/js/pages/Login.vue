<template>
    <div class="card my-4">
        <div class="card-body">
            <h2 class="card-title text-center">Login</h2>

            <form>
                <div class="mb-3">
                    <label for="email" class="form-label">
                        Email address
                    </label>

                    <input type="email" class="form-control" id="email" aria-describedby="emailHelp" v-model="loginForm.email" required>
                    
                    <div id="emailHelp" class="form-text">
                        We'll never share your email with anyone else.
                    </div>
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
            login() {
                if(this.validateForm()) {
                    console.log(this.loginForm)

                    axios.post('api/auth/login', {
                        email: this.loginForm.email,
                        password: this.loginForm.password
                    })
                        .then(res => {
                            console.log(res)

                            this.$store.commit('login', {
                                user: res.data.data,
                                token: res.data.token
                            })

                            this.$session.start()
                            this.$session.set('user', res.data.data)
                            this.$session.set('token', res.data.token)

                            this.$swal({
                                icon: 'success',
                                title: 'Logined Successfully!'
                            })

                            this.resetForm()

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
