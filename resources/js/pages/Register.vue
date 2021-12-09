<template>
    <div class="card my-4">
        <div class="card-body">
            <h2 class="card-title text-center">Register</h2>

            <form>
                <div class="mb-3">
                    <label for="name" class="form-label">
                        Name
                    </label>

                    <input type="text" class="form-control" id="name" v-model="registerForm.name" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">
                        Email address
                    </label>

                    <input type="email" class="form-control" id="email" aria-describedby="emailHelp" v-model="registerForm.email" required>
                    
                    <div id="emailHelp" class="form-text">
                        We'll never share your email with anyone else.
                    </div>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>

                    <input type="password" class="form-control" id="password" v-model="registerForm.password" required>
                </div>

                <div class="mb-3">
                    <label for="passwordConfirm" class="form-label">Confirm Password</label>

                    <input type="password" class="form-control" id="passwordConfirm" v-model="registerForm.passwordConfirm" required>
                </div>

                <button type="submit" class="btn btn-primary" @click.prevent="register">
                    Submit
                </button>
            </form>
        </div>
    </div>
</template>

<script>
    export default {
        data: () => ({
            registerForm: {
                name: '',
                email: '',
                password: '',
                passwordConfirm: ''
            }
        }),
        methods: {
            register() {
                if(this.validateForm()) {
                    console.log(this.registerForm)

                    axios.post('api/auth/register', {
                        name: this.registerForm.name,
                        email: this.registerForm.email,
                        password: this.registerForm.password
                    })
                        .then(res => {
                            console.log(res)

                            this.$store.commit('login', {
                                user: res.data.data,
                                token: res.data.token
                            })

                            this.$session.start()
                            this.$session.set('user', res.data.user)
                            this.$session.set('token', res.data.token)

                            this.$swal({
                                icon: 'success',
                                title: 'Registered Successfully!'
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
                    this.registerForm.name &&
                    this.registerForm.email &&
                    this.registerForm.password &&
                    this.registerForm.passwordConfirm === this.registerForm.password
                ) {
                    return true;
                } else {
                    return false;
                }
            },
            resetForm() {
                this.registerForm.name = ''
                this.registerForm.email = ''
                this.registerForm.password = ''
                this.registerForm.passwordConfirm = ''
            }
        }
    }
</script>
