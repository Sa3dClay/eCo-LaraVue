<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AddProductRequest;

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
        ]);
    }

    // Categories
    public function getCategories()
    {
        $categories = (new CategoryController)->getCategories();

        return response()->json([
            'categories' => $categories
        ]);
    }

    // Product
    public function addProduct(AddProductRequest $req)
    {
        (new ProductController)->addProduct($req);
    }
}
