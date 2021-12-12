<template>
    <div class="row justify-content-center my-4">
        <div
            class="col-10 col-md-6 col-lg-4 my-2"
            v-for="product in products" :key="product.id"
        >
            <div class="card">
                <img :src="'../img/products/'+product.image" alt="image" class="card-img-tot">
                <div class="card-body text-center">
                    <h4 class="card-title">{{ product.name }}</h4>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
    data: () => ({
        products: []
    }),
    computed: {
        ...mapGetters ({
            user: 'getUser'
        })
    },
    created() {
        if(!this.user) {
            this.$router.push('/')
        } else {
            // get products
            axios.get('/api/products')
                .then(res => {
                    this.products = res.data.products
                })
                .catch(err => {
                    console.log(err)
                })
        }
    }
}
</script>
