<template>
    <div class="row justify-content-center my-4">
        <div class="col-12 col-md-10 col-lg-6">
            <div v-if="products.length < 1">
                <h2 class="text-center">Your Cart Is Empty</h2>
            </div>

            <div
                v-else
                class="card my-2"
                v-for="(product, index) in products"
                :key="index"
            >
                <div class="card-body text-center">
                    <div class="row justify-content-center">
                        <div class="col-10 col-md-3 my-2">
                            <img :src="product.image" alt="image" class="img-fluid productImg">
                        </div>

                        <div class="col-6 col-md-5 my-2 py-2">
                            <h4 class="card-title text-uppercase">{{ product.name }}</h4>

                            <p class="card-text text-secondary">{{ product.SKU }}</p>
                        </div>
                        
                        <div class="col-4 col-md-2 my-2">
                            <div class="details">
                                <p class="bg-dark my-1">{{ product.brand }}</p>

                                <p class="bg-primary my-1">{{ product.category }}</p>
                            </div>
                        </div>

                        <div class="col-2 col-md-1 my-2 py-2">
                            <button
                                class="btn btn-danger p-2"
                                @click.prevent="deleteFromCart(product.id, index)"
                            >
                                <i class="bi bi-x-lg icon"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style lang="scss" scoped>
    .productImg {
        max-height: 200px;
    }
</style>

<script>
import { mapGetters } from 'vuex'

export default {
    data: () => ({
        products: []
    }),
    methods: {
        deleteFromCart(id, index) {
            // console.log(id, index)

            axios.delete('/api/cart/delete/'+id)
                .then(res => {
                    // console.log(res)

                    this.products.splice(index, 1)
                })
                .catch(err => {
                    console.log(err.response)
                })
        }
    },
    computed: {
        ...mapGetters ({
            user: 'getUser'
        })
    },
    created() {
        if(!this.user) {
            this.$router.push('/')
        } else {
            // get cart products
            axios.get('/api/cart')
                .then(res => {
                    // console.log(res.data)

                    this.products = res.data.products
                })
                .catch(err => {
                    console.log(err.response)
                })
        }
    }
}
</script>
