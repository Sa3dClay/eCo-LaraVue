<?php

namespace App\Http\Controllers;

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
}
