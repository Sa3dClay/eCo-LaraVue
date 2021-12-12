<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function getProducts()
    {
        return Product::all();

        // need to get brand and category for each
    }

    public function addProduct($req)
    {
        // get last product id
        $last_product = Product::select('id')
            ->orderByDesc('id')
            ->first();
        $last_id = $last_product->id;
        // current product id
        if($last_id > 0) {
            $current_id = $last_id + 1;
        } else {
            $current_id = 1;
        }
        // get product name
        $product_name = $req->name;
        // generate sku
        $sku = Str::upper( Str::limit($product_name, 3, '') . $current_id . "-" . Str::random(5) );

        // get product image
        $product_image = $req->image;
        // upload image to files
        $image_name = time().'.' . explode('/', explode(':', substr($product_image, 0, strpos($product_image, ';')))[1])[1];
        \Image::make($product_image)->save(public_path('img/products/').$image_name);
        $req->merge(['image' => $image_name]);

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
}
