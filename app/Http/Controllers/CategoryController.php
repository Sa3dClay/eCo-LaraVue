<?php

namespace App\Http\Controllers;

use App\Models\Category;

class CategoryController extends Controller
{
    public function index() {
        $categories = Category::with('brands')->get();

        return response()->json([
            'categories' => $categories
        ], 200);
    }
}
