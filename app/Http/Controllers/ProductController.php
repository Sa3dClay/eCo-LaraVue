<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function getProduct($id)
    {
        $product = Product::find($id);

        $mode = 'id';
        $product = $this->getProductDetails($product, $mode);

        return $product;
    }

    public function getProducts()
    {
        $products = Product::all();
        
        $merged_products = $this->mergeProductsDetails($products);

        return $merged_products;
    }

    public function mergeProductsDetails($products)
    {
        $merged_products = array();

        foreach($products as $product) {
            $product = $this->getProductDetails($product);

            array_push($merged_products, $product);
        }

        return $merged_products;
    }

    public function getProductDetails($product, $mode='name')
    {
        $brand_category = DB::table('product_brand_category')
            ->where('product_id', $product->id)
            ->select('brand_id', 'category_id')
            ->get();
        
        $brand_id = $brand_category[0]->brand_id;
        $category_id = $brand_category[0]->category_id;

        // id mode
        if($mode == 'id') {
            $product->brand = $brand_id;
            $product->category = $category_id;

            return $product;
        }

        $brand_name = (DB::table('brands')
            ->where('id', $brand_id)
            ->select('name')
            ->get())[0]->name;
        
        $category_name = (DB::table('categories')
            ->where('id', $category_id)
            ->select('name')
            ->get())[0]->name;

        $product->brand = $brand_name;
        $product->category = $category_name;

        return $product;
    }

    public function uploadImage($image)
    {
        // set image name
        // $image_name = time().'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];

        // upload image to public folder
        // \Image::make($image)->save(public_path('img/products/') . $image_name);

        // upload image to cloudinary and get image url
        $image_url = cloudinary()->upload($image)->getSecurePath();

        return $image_url;
    }

    public function getProductNextId()
    {
        // get last product id
        $last_product = Product::select('id')
            ->orderByDesc('id')
            ->first();
        
        // check empty table
        if( isset($last_product) && $last_product->id > 0 ) {
            $current_id = $last_product->id + 1;
        } else {
            $current_id = 1;
        }

        return $current_id;
    }

    public function generateSKU($product_name, $id=0)
    {
        // get product id by last one
        if($id == 0) {
            $current_id = $this->getProductNextId();
        } else {
            $current_id = $id;
        }

        // generate sku code by name and id
        $sku = Str::upper( Str::limit($product_name, 3, '') . $current_id . "-" . Str::random(5) );

        return $sku;
    }

    public function addProduct($req)
    {
        // generate sku
        $sku = $this->generateSKU($req->name);

        // upload image
        $image_url = $this->uploadImage($req->image);

        // update image
        $req->merge(['image' => $image_url]);

        // save the new product
        $product = Product::create([
            'name'      =>  $req->name,
            'image'     =>  $req->image,
            'SKU'       =>  $sku
        ]);

        // insert into product_brand_category
        DB::table('product_brand_category')->insert([
            'category_id'   => $req->category,
            'product_id'    => $product->id,
            'brand_id'      => $req->brand,
        ]);
    }

    public function updateProduct($id, $req)
    {
        $product = Product::find($id);

        // check name
        if($req->name != $product->name) {
            // generate new sku
            $sku = $this->generateSKU($req->name, $id);

            // update name
            $product->name = $req->name;

            // update sku
            $product->SKU = $sku;
        }

        // check image
        if($req->image) {
            // upload image
            $image_url = $this->uploadImage($req->image);

            // update image
            $product->image = $image_url;
        }

        // save updates
        $product->save();

        // update brand and category
        DB::table('product_brand_category')
            ->where('product_id', $id)
            ->update([
                'category_id'   => $req->category,
                'brand_id'      => $req->brand
            ]);
    }

    public function deleteProduct($id)
    {
        $product = Product::find($id);

        DB::table('product_brand_category')
            ->where('product_id', $id)
            ->delete();

        $product->delete();
    }
}
