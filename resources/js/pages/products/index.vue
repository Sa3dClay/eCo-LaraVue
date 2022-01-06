<template>
    <div class="row justify-content-center my-4">
        <div class="col-12">
            <div v-if="products.length > 0">
                <h1 class="text-center">
                    Show 
                    <a class="btn btn-dark py-1 px-2" @click.prevent="resetFilters">All</a> 
                    Products or filter by
                    <button class="btn btn-success py-1 px-2" disabled>Brands</button>
                    /
                    <button class="btn btn-primary py-1 px-2" disabled>Categories</button>
                </h1>
            </div>

            <div v-else>
                <h1 class="text-center">No Products Added Yet!</h1>
            </div>
        </div>

        <div
            class="col-10 col-md-4 col-lg-3 my-2"
            v-for="product in filterProducts" :key="product.id"
        >
            <div class="card">

                <div class="box">
                    <div class="overlay"></div>

                    <img :src="product.image" alt="image" class="img-fluid">

                    <div class="actions">
                        <div v-if="user.role == 0">
                            <button
                                class="btn btn-success"
                                @click.prevent="update(product.id)"
                            >Edit Product</button>
                        </div>

                        <div v-else>
                            <button
                                v-if="notInCart(product.id)"
                                :disabled="!canClick"
                                class="btn btn-success"
                                @click.prevent="addToCart(product.id)"
                            >Add To Cart</button>

                            <button
                                v-else
                                disabled
                                class="btn btn-success"
                            >In Cart</button>
                        </div>
                    </div>
                </div>

                <div class="card-body text-center">
                    <h4 class="card-title text-uppercase">{{ product.name }}</h4>

                    <p class="card-text text-secondary">{{ product.SKU }}</p>

                    <div class="details">
                        <div class="row">
                            <div class="col-6">
                                <a
                                    class="d-block btn btn-dark py-1 px-2"
                                    @click.prevent="applyBrand(product.brand_name)"
                                >
                                    {{ product.brand_name }}
                                </a>
                            </div>

                            <div class="col-6">
                                <a
                                    class="d-block btn btn-primary py-1 px-2"
                                    @click.prevent="applyCategory(product.category_name)"
                                >
                                    {{ product.category_name }}
                                </a>
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
    }
</style>

<style lang="scss">
    .details {
        p {
            color: #fff;
            padding: 4px;
            margin: 0;
        }
        .btn {
            transition: all 0.3s;
            border-radius: 0;
        }
    }
</style>

<script>
import { mapGetters } from 'vuex'

export default {
    data: () => ({
        products: [],
        cartProducts: [],
        canClick: true,
        brandFilter: '',
        categoryFilter: '',
        currentFilterType: ''
    }),
    methods: {
        update(id) {
            // console.log('product:', id)

            this.$router.push('/products/edit/'+id)
        },
        addToCart(id) {
            // console.log('product:', id)

            this.canClick = false

            axios.post('/api/cart/add', {id})
                .then(res => {
                    // console.log(res)

                    this.canClick = true

                    // this.$swal({
                    //     position: 'top-end',
                    //     icon: 'success',
                    //     title: 'Added successfully',
                    //     showConfirmButton: false,
                    //     timer: 1500
                    // })

                    this.$store.commit('increaseCartCounter')

                    this.cartProducts.push(res.data.product)
                })
                .catch(err => {
                    console.log(err.response)

                    this.canClick = true

                    this.$swal({
                        icon: 'error',
                        title: 'Something went wrong',
                        showConfirmButton: false,
                        timer: 1500
                    })
                })
        },
        notInCart(id) {
            // console.log(id)

            var check = true

            this.cartProducts.forEach(product => {
                // console.log(product.id, id)

                if(product.id == id) {
                    check = false
                }
            })

            return check
        },
        // Filters
        resetFilters() {
            this.brandFilter = ''
            this.categoryFilter = ''
        },
        applyBrand(brand) {
            this.currentFilterType = 'brand'
            this.brandFilter = brand
        },
        applyCategory(category) {
            this.currentFilterType = 'category'
            this.categoryFilter = category
        }
    },
    computed: {
        ...mapGetters ({
            user: 'getUser'
        }),
        filterProducts() {
            if(this.currentFilterType == 'brand') {
                return this.products.filter(product => {
                    return product.brand_name.match(this.brandFilter)
                })
            } else {
                return this.products.filter(product => {
                    return product.category_name.match(this.categoryFilter)
                })
            }
        }
    },
    created() {
        if(!this.user) {
            this.$router.push('/')
        } else {
            // get products
            axios.get('/api/products')
                .then(res => {
                    // console.log(res.data)

                    this.products = res.data.products
                })
                .catch(err => {
                    console.log(err.response)

                    // check if token expired
                    if(err.response.status == 401) {
                        // destroy session
                        this.$session.destroy()
                        localStorage.clear()
                        
                        // logout the user
                        this.$store.commit('logout')
                        this.$router.push('/login')
                    }
                })
            
            // get cart products
            axios.get('/api/cart')
                .then(res => {
                    // console.log(res.data)

                    this.cartProducts = res.data.products
                })
                .catch(err => {
                    console.log(err.response)
                })
        }
    }
}
</script>
