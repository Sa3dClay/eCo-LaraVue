<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function getProducts()
    {
        $products = (new ProductController)->getProducts();
        
        return response()->json([
            'products' => $products
        ]);
    }
}
