<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function getCategories()
    {
        return response()->json([
            'Categories' => Category::all()
        ]);
    }
    public function addCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string'
        ]);
        $category = Category::create([
            'name' => $request->name
        ]);
        return response()->json([
            'message' => 'Category Created Successfully',
            'Category' => $category
        ]);
    }

    public function updateCategory(Request $request, $id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response([
                'message' => 'Category not found'
            ]);
        }
        $category->update([
            'name' => $request->name
        ]);
        return response()->json([
            'message' => 'Category updated',
            'Category' => $category
        ]);
    }

    public function delete($id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response([
                'message' => 'Category not found'
            ]);
        }
        $category->delete();
        return response()->json([
            'message' => 'Category deleted'
        ]);
    }
}
