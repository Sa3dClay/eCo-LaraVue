<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Str;

class ProductService
{
    /**
     * @param product
     * 
     * get product brand & category names
     * 
     * @return product:Product
     */
    public function getProductDetails($product)
    {
        $product->brand_name = $product->brand_name;
        $product->category_name = $product->category_name;

        return $product;
    }

    /**
     * @param products
     * 
     * get all products brand & category names
     * 
     * @return products:Product
     */
    public function getAllProductsDetails($products)
    {
        $detailed_products = array();

        foreach($products as $product) {
            $product = $this->getProductDetails($product);

            array_push($detailed_products, $product);
        }

        return $detailed_products;
    }

    /**
     * @param id,mode
     * 
     * mode id      => to get brand & category ids
     * mode name    => to get brand & category names
     * 
     * @return product:Product
     */
    public function getProduct($id, $mode='id')
    {
        $product = Product::find($id);

        if($mode == 'name')
            return $this->getProductDetails($product);

        return response()->json([
            'product' => $product
        ], 200);
    }

    /**
     * @return products:Product
     */
    public function getProducts()
    {
        $products = Product::all();
        
        $products = $this->getAllProductsDetails($products);

        return response()->json([
            'products' => $products
        ], 200);
    }

    /**
     * get product id by last +1
     * and set to 1 if first one
     * 
     * @return id:integer
     */
    public function getNextProductId()
    {
        $last_product = Product::orderByDesc()->first();
        
        if( isset($last_product) && $last_product->id > 0 ) {
            $id = $last_product->id + 1;
        } else {
            $id = 1;
        }

        return $id;
    }

    /**
     * @param product_name,id
     * 
     * generate sku code using product name and expected id
     * id mode default 0 if new product and else for update
     * 
     * @return sku:string
     */
    public function generateSKU($product_name, $id = 0)
    {
        if($id == 0) {
            $current_id = $this->getNextProductId();
        } else {
            $current_id = $id;
        }

        $sku = Str::upper( Str::limit($product_name, 3, '') . $current_id . "-" . Str::random(5) );

        return $sku;
    }

    /**
     * @param image
     * 
     * upload image to cloudinary
     * 
     * @return image_url:string
     */
    public function uploadImageToCloud($image)
    {
        $image_url = cloudinary()->upload($image)->getSecurePath();

        return $image_url;
    }

    /**
     * @param request
     * 
     * create new product
     * 
     * @return void
     */
    public function addNewProduct($request)
    {
        $sku = $this->generateSKU($request->name);

        $request->image = $this->uploadImageToCloud($request->image);

        Product::create([
            'SKU'           =>  $sku,
            'name'          =>  $request->name,
            'image'         =>  $request->image,
            'brand_id'      =>  $request->brand,
            'category_id'   =>  $request->category
        ]);
    }

    /**
     * @param id,request
     * 
     * update product
     * 
     * @return void
     */
    public function updateProduct($id, $request)
    {
        $product = Product::find($id);

        if($request->name != $product->name) {
            $sku = $this->generateSKU($request->name, $id);

            $product->name  = $request->name;
            $product->SKU   = $sku;
        }

        // @FIXME: remove old image
        if($request->image)
            $product->image = $this->uploadImageToCloud($request->image);

        if($request->brand)
            $product->brand_id = $request->brand;
        
        if($request->category)
            $product->category_id = $request->category;

        $product->save();
    }

    /**
     * @param id
     * 
     * remove product
     * 
     * @return void
     */
    public function deleteProduct($id)
    {
        $product = Product::find($id);

        // @FIXME: remove product image

        $product->delete();
    }
}
