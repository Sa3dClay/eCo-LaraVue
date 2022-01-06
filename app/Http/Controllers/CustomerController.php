<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    // Products
    public function getProducts()
    {
        $products = (new ProductController)->getProducts();
        
        return response()->json([
            'products' => $products
        ]);
    }

    // Cart
    public function addCartProduct(Request $req)
    {
        $user_id = Auth::id();
        $product_id = $req->id;

        $product = (new CartController)->addCartProduct($user_id, $product_id);

        return response()->json([
            'product' => $product
        ]);
    }

    public function getCartProducts()
    {
        $carts = Auth::user()->carts;

        $products = (new CartController)->getCartProducts($carts);

        return response()->json([
            'products' => $products
        ]);
    }

    public function deleteCartProduct($product_id)
    {
        $user_id = Auth::id();

        (new CartController)->deleteCartProduct($user_id, $product_id);
    }
}
