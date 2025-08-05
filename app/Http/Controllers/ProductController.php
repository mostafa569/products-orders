<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
     
    public function index(): JsonResponse
    {
        $products = Product::all();
        return response()->json([
            'success' => true,
            'data' => ProductResource::collection($products)
        ]);
    }

     
    public function store(StoreProductRequest $request): JsonResponse
    {
        $product = Product::create($request->validated());
        
        return response()->json([
            'success' => true,
            'message' => 'Product created successfully',
            'data' => new ProductResource($product)
        ], 201);
    }

    
    public function show(string $id): JsonResponse
    {
        $product = Product::findOrFail($id);
        
        return response()->json([
            'success' => true,
            'data' => new ProductResource($product)
        ]);
    }

     
    public function update(UpdateProductRequest $request, string $id): JsonResponse
    {
        $product = Product::findOrFail($id);
        $product->update($request->validated());
        
        return response()->json([
            'success' => true,
            'message' => 'Product updated successfully',
            'data' => new ProductResource($product)
        ]);
    }

    
    public function destroy(string $id): JsonResponse
    {
        $product = Product::findOrFail($id);
        
         
        if ($product->orderItems()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete product that is part of an order'
            ], 400);
        }
        
        $product->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Product deleted successfully'
        ]);
    }
}