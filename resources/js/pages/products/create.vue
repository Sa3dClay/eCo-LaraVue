<template>
    <div class="row justify-content-center">
        <div class="col-11 col-md-8 col-lg-5">
            <div class="card my-4">
                <div class="card-body">
                    <h2 class="card-title text-center">Add New Product</h2>

                    <form>
                        <div class="mb-3">
                            <label for="name" class="form-label">
                                Product Name
                            </label>

                            <input type="text" class="form-control" id="name" v-model="form.name" required>
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">Product Image</label>

                            <input
                                @change='upload_image'
                                class="form-control"
                                type="file"
                                id="image"
                            >

                            <img :src="form.image" v-show="showImage" width="100" height="100" class="my-2">
                        </div>

                        <div class="mb-3">
                            <label for="brand" class="form-label">Brand Name</label>

                            <select class="form-select" id="brand" v-model="form.brand">
                                <option
                                    v-for="brand in brands"
                                    :value="brand.id"
                                    :key="brand.id"
                                >
                                    {{ brand.name }}
                                </option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="category" class="form-label">Brand Name</label>

                            <select class="form-select" id="category" v-model="form.category">
                                <option
                                    v-for="category in categories"
                                    :value="category.id"
                                    :key="category.id"
                                >
                                    {{ category.name }}
                                </option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary" @click.prevent="create">
                            Submit
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
    data: () => ({
        form: {
            name: '',
            image: '',
            brand: 1,
            category: 1
        },
        brands: [],
        categories: [],
        showImage: false
    }),
    methods: {
        create(
            event,
            name = this.form.name,
            image = this.form.image,
            brand = this.form.brand,
            category = this.form.category
        ) {
            // console.log('create:', name, image, brand, category)

            if(this.validateForm()) {
                // axios
                axios.post('api/products/create', {
                    name,
                    image,
                    brand,
                    category
                })
                    .then(res => {
                        // console.log(res)

                        this.$swal({
                            icon: 'success',
                            title: 'Product Added Successfully!'
                        })

                        this.resetForm()
                    })
                    .catch(err => {
                        console.log(err)
                    })
            } else {
                this.$swal({
                    icon: 'info',
                    title: 'Data Missing'
                })
            }
        },
        resetForm() {
            this.form.name = ''
            this.form.image = ''
            this.form.brand = ''
            this.form.category = ''
            this.showImage = false
        },
        validateForm() {
            if(
                this.form.name &&
                this.form.image &&
                this.form.brand &&
                this.form.category
            ) {
                return true
            } else {
                return false
            }
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
            this.$router.push('/')
        } else {
            // get brands
            axios.get('/api/brands')
                .then(res => {
                    console.log('brands:', res.data)

                    this.brands = res.data.brands
                })
                .catch(err => {
                    console.log(err)
                })

            // get categories
            axios.get('/api/categories')
                .then(res => {
                    console.log('categories:', res.data)

                    this.categories = res.data.categories
                })
                .catch(err => {
                    console.log(err)
                })
        }
    }
}
</script>
