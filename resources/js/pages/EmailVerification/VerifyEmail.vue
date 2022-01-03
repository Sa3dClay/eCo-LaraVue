<template>
    <div class="row justify-content-center my-4">
        <div class="col-10 col-md-8 col-lg-6 my-4">
            <div class="card text-center my-5">
                <div class="card-body my-5">
                    <h1 class="card-title my-5">Please Verify Your Email</h1>

                    <p class="card-text text-secondary">
                        Can't find your email?

                        <button class="btn btn-primary mx-2" @click.prevent="resend_email">Resend Verification Link</button>
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    methods: {
        resend_email() {
            axios.post('api/auth/email/verify/resend')
                .then(res => {
                    console.log(res)

                    this.$swal({
                        icon: 'success',
                        title: res.data.message,
                        showConfirmButton: true
                    })
                })
                .catch(err => {
                    console.log(err.response)
                })
        }
    }
}
</script>
