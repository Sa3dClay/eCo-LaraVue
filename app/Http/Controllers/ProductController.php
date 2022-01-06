<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Str;
// use Illuminate\Http\Request;
// use Illuminate\Support\Carbon;
// use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * get single product by id
     * default mode id to get brand & category id
     * name mode to get brand & category names
     */
    public function getProduct($id, $mode='id')
    {
        $product = Product::find($id);

        if($mode == 'name')
        {
            $product = $this->getProductDetails($product);
        }

        return $product;
    }

    /**
     * get all products
     */
    public function getProducts()
    {
        $products = Product::all();
        
        $merged_products = $this->mergeProductsDetails($products);

        return $merged_products;
    }

    /**
     * get all products with brands and categories
     */
    public function mergeProductsDetails($products)
    {
        $merged_products = array();

        foreach($products as $product) {
            $product = $this->getProductDetails($product);

            array_push($merged_products, $product);
        }

        return $merged_products;
    }

    /**
     * get product brand & category names
     */
    public function getProductDetails($product)
    {
        $product->brand_name = $product->brand->name;
        $product->category_name = $product->category->name;

        return $product;
    }

    /**
     * upload image to local file
     */
    public function uploadImageLocal($image)
    {
        // $image_name = time().'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
        // \Image::make($image)->save(public_path('img/products/') . $image_name);
        // $image_url = public_path('img/products/') . $image_name;

        // return $image_url;
    }

    /**
     * upload image to cloudinary
     */
    public function uploadImageCloud($image)
    {
        $image_url = cloudinary()->upload($image)->getSecurePath();

        return $image_url;
    }

    /**
     * get product id from last one
     * for first product return id = 1
     */
    public function getProductNextId()
    {
        $last_product = Product::select('id')
            ->orderByDesc('id')
            ->first();
        
        if( isset($last_product) && $last_product->id > 0 ) {
            $current_id = $last_product->id + 1;
        } else {
            $current_id = 1;
        }

        return $current_id;
    }

    /**
     * generate sku code using product name and id
     * default id = 0 for new product
     * id mode for update product
     */
    public function generateSKU($product_name, $id=0)
    {
        if($id == 0) {
            $current_id = $this->getProductNextId();
        } else {
            $current_id = $id;
        }

        $sku = Str::upper( Str::limit($product_name, 3, '') . $current_id . "-" . Str::random(5) );

        return $sku;
    }

    /**
     * create new product
     * 
     * generate sku code
     * upload image to cloud
     * update image with image url
     * create product with details (brand & category)
     */
    public function addProduct($req)
    {
        $sku = $this->generateSKU($req->name);

        $image_url = $this->uploadImageCloud($req->image);

        $req->merge(['image' => $image_url]);

        Product::create([
            'SKU'           =>  $sku,
            'name'          =>  $req->name,
            'image'         =>  $req->image,
            'brand_id'      =>  $req->brand,
            'category_id'   =>  $req->category
        ]);
    }

    /**
     * update product with id
     * 
     * for name changing: generate new sku code, update name & sku
     * for image request: upload new image, update image url
     * do not remove old image from cloud
     * update product with details (brand & category)
     */
    public function updateProduct($id, $req)
    {
        $product = Product::find($id);

        if($req->name != $product->name) {
            $sku = $this->generateSKU($req->name, $id);

            $product->name = $req->name;

            $product->SKU = $sku;
        }

        if($req->image) {
            $image_url = $this->uploadImageCloud($req->image);

            $product->image = $image_url;
        }

        if($req->brand) $product->brand_id = $req->brand;
        if($req->category) $product->category_id = $req->category;

        $product->save();
    }

    /**
     * remove product using id
     */
    public function deleteProduct($id)
    {
        $product = Product::find($id);

        $product->delete();
    }
}
