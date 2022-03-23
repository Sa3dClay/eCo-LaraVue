<?php

namespace App\Http\Controllers;

use App\Models\Brand;

class BrandController extends Controller
{
    public function index() {
        $brands = Brand::with('categories')->get();

        return response()->json([
            'brands' => $brands
        ], 200);
    }
}
