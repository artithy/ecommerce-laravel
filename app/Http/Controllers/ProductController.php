<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function createProduct(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'discount_price' => 'required|numeric',
            'image' => 'nullable|string',
            'category_id' => 'nullable|boolean',
        ]);

        $product = Product::create([
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'discount_price' => $request->discount_price,
            'image' => $request->image,
            'category_id' => $request->category_id,
            'status' => $request->status ?? true,
        ]);

        return response()->json([
            'message' => 'Product created successfully',
            'product' => $product
        ]);
    }

    public function getAllProducts(Request $request)
    {
        $products = Product::all();
        return response()->json($products);
    }

    public function getSingleProduct($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json([
                'product not found'
            ], 404);
        }
    }
    public function getProductsByCategory(Request $request)
    {
        if (!$request->category) {
            return response()->json([
                'message' => 'Category id required'
            ]);
        }

        $products = Product::where('category_id', $request->category)->get();
        return response()->json($products);
    }
    public function updateProduct(Request $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'message' => 'Product not found'
            ]);
        }

        $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'discount_price' => 'nullable|numeric',
            'image' => 'nullable|string',
            'category_id' => 'required|integer',
            'status' => 'boolen',
        ]);

        $product->update($request->all());
        return response()->json([
            'message' => 'Product update successfully',
            'product' => $product
        ]);
    }

    public function deleteProduct(Request $product, $id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json([
                'message' => 'Product not found'
            ]);
        }
        $product->delete();

        return response()->json([
            'message' => 'Product deleted successfully'
        ]);
    }
}
