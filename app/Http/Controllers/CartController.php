<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CartService;
use App\Services\ProductService;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    private $cartService;
    private $productService;

    public function __construct(CartService $cartService, ProductService $productService)
    {
        $this->cartService = $cartService;
        $this->productService = $productService;
    }

    public function index()
    {
        return $this->cartService->getCartProducts(Auth::user()->cart_items, $this->productService);
    }

    public function store(Request $request)
    {
        return $this->cartService->addProductToCart(Auth::id(), $request->id, $this->productService);
    }

    public function delete($product_id) {
        $this->cartService->deleteProductFromCart(Auth::id(), $product_id);
    }
}
