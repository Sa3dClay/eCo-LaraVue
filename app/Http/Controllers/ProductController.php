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
        $products = Product::all();
        
        $merged_products = $this->getDetails($products);

        return $merged_products;
    }

    public function getDetails($products)
    {
        $merged_products = array();

        foreach($products as $product) {
            $brand_category = DB::table('product_brand_category')
                ->where('product_id', $product->id)
                ->select('brand_id', 'category_id')
                ->get();
            
            $brand_id = $brand_category[0]->brand_id;
            $category_id = $brand_category[0]->category_id;

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

            array_push($merged_products, $product);
        }

        return $merged_products;
    }

    public function addProduct($req)
    {
        // get last product id
        $last_product = Product::select('id')
            ->orderByDesc('id')
            ->first();
        
        if( isset($last_product) && $last_product->id > 0 ) {
            $current_id = $last_product->id + 1;
        } else {
            $current_id = 1;
        }

        // get product name
        $product_name = $req->name;
        
        // generate sku code
        $sku = Str::upper( Str::limit($product_name, 3, '') . $current_id . "-" . Str::random(5) );

        // get product image
        $product_image = $req->image;
        
        // upload image to folder
        $image_name = time().'.' . explode('/', explode(':', substr($product_image, 0, strpos($product_image, ';')))[1])[1];
        \Image::make($product_image)->save(public_path('img/products/') . $image_name);
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
