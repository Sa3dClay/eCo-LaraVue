<?php

namespace App\Http\Controllers;

use App\Services\ProductService;
use App\Http\Requests\AddProductRequest;
use App\Http\Requests\UpdateProductRequest;

class ProductController extends Controller
{
    private $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index()
    {
        return $this->productService->getProducts();
    }

    public function find($id)
    {
        return $this->productService->getProduct($id);
    }

    public function store(AddProductRequest $request)
    {
        return $this->productService->addNewProduct($request);
    }

    public function update($id, UpdateProductRequest $request)
    {
        return $this->productService->updateProduct($id, $request);
    }

    public function delete($id)
    {
        return $this->productService->deleteProduct($id);
    }
}
