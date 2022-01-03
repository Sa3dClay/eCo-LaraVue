<template>
    <div class="row justify-content-center my-4">
        <div class="col-10 col-md-8 col-lg-6 my-4">
            <div class="card text-center my-5">
                <div class="card-body my-5">
                    <h1 class="card-title my-5">Verifying Your Email...</h1>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    created() {
        let email_verify_url = this.$route.query.email_verify_url
        let signature = this.$route.query.signature

        axios.get(email_verify_url + signature)
            .then(res => {
                    console.log(res)

                    this.$swal({
                        icon: 'success',
                        title: res.data.message,
                        showConfirmButton: true
                    })

                    let verify_date = res.data.user_verify_date
                    this.$session.set('verfied', verify_date)

                    this.$router.push('/')
                })
                .catch(err => {
                    console.log(err.response)
                })
    }
}
</script>
