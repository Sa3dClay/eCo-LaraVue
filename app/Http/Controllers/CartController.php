<?php

namespace App\Http\Controllers;

use App\Models\Cart;
// use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    /**
     * add product to cart with user_id & product_id
     * get product details (brand & category)
     * return product to update store (in cart button)
     */
    public function addCartProduct($user_id, $product_id)
    {
        Cart::create([
            'user_id'       =>  $user_id,
            'product_id'    =>  $product_id
        ]);

        $mode = 'name';
        
        $product = (new ProductController)->getProduct($product_id, $mode);

        return $product;
    }

    /**
     * get all products from user cart by user_id
     * get details (brand & category) for each product
     */
    public function getCartProducts($carts)
    {
        $products = array();
        $mode = 'name';

        foreach($carts as $cart) {
            $product = (new ProductController)->getProduct($cart->product_id, $mode);

            array_push($products, $product);
        }

        return $products;
    }

    /**
     * delete product from cart using user_id & product_id
     */
    public function deleteCartProduct($user_id, $product_id)
    {
        Cart::where('product_id', $product_id)
            ->where('user_id', $user_id)
            ->delete();
    }
}
