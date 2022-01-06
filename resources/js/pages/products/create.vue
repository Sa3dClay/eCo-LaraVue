<template>
    <div class="row justify-content-center my-4">
        <div class="col-11 col-md-8 col-lg-5">
            <div class="card">
                <div class="card-body">
                    <h2 class="card-title text-center">Add New Product</h2>

                    <form @submit.prevent="create" @keydown="form.onKeydown($event)">
                        <div class="mb-3">
                            <label for="name" class="form-label">Product Name</label>

                            <input
                                id="name"
                                name="name"
                                type="text"
                                v-model="form.name"
                                class="form-control"
                                required
                            >

                            <div
                                v-if="form.errors.has('name')"
                                v-html="form.errors.get('name')"
                                class="bg-danger rounded text-white text-center p-2 m-2"
                            />
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">Product Image</label>

                            <input
                                @change='upload_image'
                                class="form-control"
                                name="image"
                                type="file"
                                id="image"
                                required
                            >

                            <div
                                v-if="form.errors.has('image')"
                                v-html="form.errors.get('image')"
                                class="bg-danger rounded text-white text-center p-2 m-2"
                            />

                            <img :src="form.image" v-if="showImage" width="100" height="100" class="my-2">
                        </div>

                        <div class="mb-3">
                            <label for="brand" class="form-label">Brand Name</label>

                            <select
                                id="brand"
                                name="brand"
                                class="form-select"
                                v-model="form.brand"
                                required
                            >
                                <option
                                    v-for="brand in brands"
                                    :value="brand.id"
                                    :key="brand.id"
                                >
                                    {{ brand.name }}
                                </option>
                            </select>

                            <div
                                v-if="form.errors.has('brand')"
                                v-html="form.errors.get('brand')"
                                class="bg-danger rounded text-white text-center p-2 m-2"
                            />
                        </div>

                        <div class="mb-3">
                            <label for="category" class="form-label">Brand Name</label>

                            <select
                                id="category"
                                name="category"
                                class="form-select"
                                v-model="form.category"
                                required
                            >
                                <option
                                    v-for="category in categories"
                                    :value="category.id"
                                    :key="category.id"
                                >
                                    {{ category.name }}
                                </option>
                            </select>

                            <div
                                v-if="form.errors.has('category')"
                                v-html="form.errors.get('category')"
                                class="bg-danger rounded text-white text-center p-2 m-2"
                            />
                        </div>

                        <button type="submit" class="btn btn-primary" :disabled="form.busy">
                            Create
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapGetters } from 'vuex'
import Form from 'vform'

export default {
    data: () => ({
        form: new Form({
            name: '',
            image: '',
            brand: 1,
            category: 1
        }),
        brands: [],
        categories: [],
        showImage: false,
    }),
    methods: {
        // create
        async create() {
            await this.form.post('api/products/create')
                .then(res => {
                    console.log(res)

                    this.$swal({
                        icon: 'success',
                        title: 'Product Added Successfully!',
                        showConfirmButton: false,
                        timer: 1500
                    })

                    this.form.reset()
                    this.showImage = false
                })
                .catch(err => {
                    console.log(err.response)

                    this.$swal({
                        icon: 'error',
                        title: 'Sorry, something went wrong!',
                        showConfirmButton: true,
                    })
                })
        },
        // upload image on change
        upload_image(e) {
            let file = e.target.files[0]
            let reader = new FileReader()

            // check for image type
            if(this.is_image(file)) {
                // check for image size
                if(file['size'] < 1000000)
                {
                    reader.onloadend = () => {
                        this.showImage = true
                        this.form.image = reader.result
                    }
                    reader.readAsDataURL(file)
                } else {
                    this.$swal({
                        icon: 'error',
                        title: 'File size can not be bigger than 1 MB'
                    })
                }
            } else {
                this.$swal({
                    icon: 'error',
                    title: 'File type must be an image'
                })
            }
        },
        // check image type
        is_image(file) {
            let accepts = ['image/png', 'image/jpg', 'image/jpeg']
            return file && accepts.includes(file['type'])
        }
    },
    computed: {
        ...mapGetters ({
            user: 'getUser'
        })
    },
    created() {
        if(!this.user.role == '0') {
            this.$router.push('/store')
        } else {
            // get brands
            axios.get('/api/brands')
                .then(res => {
                    // console.log('brands:', res.data)

                    this.brands = res.data.brands
                })
                .catch(err => {
                    console.log(err)
                })

            // get categories
            axios.get('/api/categories')
                .then(res => {
                    // console.log('categories:', res.data)

                    this.categories = res.data.categories
                })
                .catch(err => {
                    console.log(err)
                })
        }
    }
}
</script>
