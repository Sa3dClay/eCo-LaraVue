<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function addCartProduct($user_id, $product_id)
    {
        Cart::create([
            'user_id'   =>  $user_id,
            'product_id'=>  $product_id
        ]);

        $mode = 'name';

        $product = (new ProductController)->getProduct($product_id, $mode);

        return $product;
    }

    public function getCartProducts($user_id)
    {
        $cart_products = Cart::where('user_id', $user_id)->get();
        
        $products = array();
        $mode = 'name';

        foreach($cart_products as $cart_product) {
            $product = (new ProductController)->getProduct($cart_product->product_id, $mode);

            array_push($products, $product);
        }

        return $products;
    }

    public function deleteCartProduct($user_id, $product_id)
    {
        // delete
        DB::table('carts')
            ->where('product_id', $product_id)
            ->where('user_id', $user_id)
            ->delete();
    }
}
