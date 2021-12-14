<template>
    <div class="row justify-content-center">
        <div class="col-11 col-md-8 col-lg-5">
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

                            <input type="password" class="form-control" id="password" v-model="registerForm.password" minlength="8" required>
                        </div>

                        <div class="mb-3">
                            <label for="passwordConfirm" class="form-label">Confirm Password</label>

                            <input type="password" class="form-control" id="passwordConfirm" v-model="registerForm.passwordConfirm" minlength="8" required>
                        </div>

                        <button type="submit" class="btn btn-primary" @click.prevent="register">
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
        registerForm: {
            name: '',
            email: '',
            password: '',
            passwordConfirm: ''
        }
    }),
    methods: {
        register(
            event,
            name = this.registerForm.name,
            email = this.registerForm.email,
            password = this.registerForm.password
        ) {
            // console.log("Register:", name, email, password)

            if(this.validateForm()) {
                // axios
                axios.post('api/auth/register', {
                    name: name,
                    email: email,
                    password: password
                })
                    .then(res => {
                        // console.log(res)
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

                        // reset form
                        this.resetForm()

                        // router
                        this.$router.push('/')
                    })
                    .catch(err => {
                        console.log(err.response)
                        let errorMessage = err.response.statusText

                        if(err.response.data.errors.email) {
                            errorMessage = err.response.data.errors.email[0]
                        }

                        this.$swal({
                            icon: 'error',
                            title: 'Oops...',
                            text: errorMessage,
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
                this.registerForm.password.length >= 8 &&
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
