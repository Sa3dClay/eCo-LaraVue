<template>
    <div class="row justify-content-center my-4">
        <div class="col-10">
            <h1 class="text-center">Products</h1>
        </div>

        <div
            class="col-10 col-md-4 col-lg-3 my-2"
            v-for="product in products" :key="product.id"
        >
            <div class="card">

                <div class="box">
                    <div class="overlay"></div>

                    <img :src="product.image" alt="image" class="img-fluid">

                    <div class="actions">
                        <div v-if="user.role == 0">
                            <button class="btn btn-success" @click.prevent="update(product.id)">Edit Product</button>
                        </div>

                        <div v-else>
                            <button class="btn btn-success">Add To Cart</button>
                        </div>
                    </div>
                </div>

                <div class="card-body text-center">
                    <h4 class="card-title text-uppercase">{{ product.name }}</h4>

                    <p class="card-text text-secondary">{{ product.SKU }}</p>

                    <div class="details">
                        <div class="row">
                            <div class="col-6">
                                <p class="bg-dark">{{ product.brand }}</p>
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
    .card {
        .box {
            position: relative;
            &:hover {
                .overlay {
                    opacity: 1;
                }
                .actions {
                    opacity: 1;
                }
            }
            .overlay {
                transition: all 0.3s;
                position: absolute;
                width: 100%;
                height: 100%;
                top: 0;
                left: 0;
                background-color: rgba($color: #000000, $alpha: 0.5);
                opacity: 0;
            }
            .actions {
                transition: all 0.4s;
                position: absolute;
                padding: 20px;
                width: 100%;
                bottom: 0;
                opacity: 0;
                .btn {
                    border-radius: 0;
                    width: 100%;
                }
            }
        }
        .details {
            p {
                color: #fff;
                padding: 8px;
                margin: 0;
            }
        }
    }
</style>

<script>
import { mapGetters } from 'vuex'

export default {
    data: () => ({
        products: []
    }),
    methods: {
        update(id) {
            // console.log('product:', id)

            this.$router.push('/products/edit/'+id)
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
