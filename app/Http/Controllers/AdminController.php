<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AddProductRequest;
use App\Http\Requests\UpdateProductRequest;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('isAdmin');
    }

    // Brands
    public function getBrands()
    {
        $brands = (new BrandController)->getBrands();

        return response()->json([
            'brands' => $brands
        ], 200);
    }

    // Categories
    public function getCategories()
    {
        $categories = (new CategoryController)->getCategories();

        return response()->json([
            'categories' => $categories
        ], 200);
    }

    // Products
    public function getProduct($id)
    {
        $product = (new ProductController)->getProduct($id);

        return response()->json([
            'product' => $product
        ], 200);
    }

    public function addProduct(AddProductRequest $req)
    {
        (new ProductController)->addProduct($req);
    }

    public function updateProduct($id, UpdateProductRequest $req)
    {
        (new ProductController)->updateProduct($id, $req);
    }
}
