<template>
    <div class="row justify-content-center my-4">
        <div class="col-10">
            <h1 class="text-center">Products</h1>
        </div>

        <div
            class="col-10 col-md-6 col-lg-4 my-2"
            v-for="product in products" :key="product.id"
        >
            <div class="card">
                <img :src="'../img/products/'+product.image" alt="image" class="card-img-tot">

                <div class="card-body text-center">
                    <h4 class="card-title text-uppercase">{{ product.name }}</h4>

                    <p class="card-text text-secondary">{{ product.SKU }}</p>

                    <div class="details">
                        <div class="row">
                            <div class="col-6">
                                <p class="bg-success">{{ product.brand }}</p>
                            </div>

                            <div class="col-6">
                                <p class="bg-primary">{{ product.category }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style lang="scss" scoped>
    .details {
        p {
            color: #fff;
            padding: 8px;
            margin: 0;
        }
    }
</style>

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
