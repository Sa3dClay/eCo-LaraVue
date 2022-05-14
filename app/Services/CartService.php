<?php

namespace App\Services;

use App\Models\Cart;

class CartService
{
    /**
     * @param cart_items
     * 
     * get all products from cart
     * 
     * @return products
     */
    public function getCartProducts($cart_items, ProductService $productService)
    {
        $products = array();

        foreach ($cart_items as $item) {
            $product = $productService->getProduct($item->product_id, 'name');

            array_push($products, $product);
        }

        return response()->json([
            'products' => $products
        ], 200);
    }
    
    /**
     * @param user_id,product_id
     * 
     * add product to cart
     * 
     * @return product
     */
    public function addProductToCart($user_id, $product_id, ProductService $productService)
    {
        Cart::create([
            'user_id'       =>  $user_id,
            'product_id'    =>  $product_id
        ]);

        $product = $productService->getProduct($product_id, 'name');

        return response()->json([
            'product' => $product
        ], 201);
    }

    /**
     * @param user_id,product_id
     * 
     * delete product from cart
     * 
     * @return void
     */
    public function deleteProductFromCart($user_id, $product_id)
    {
        Cart::where('product_id', $product_id)
            ->where('user_id', $user_id)
            ->delete();
    }
}
