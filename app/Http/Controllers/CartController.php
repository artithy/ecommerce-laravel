<?php

namespace App\Http\Controllers;

use App\Models\cart;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function getCart(Request $request)
    {
        $auth = $request->attributes->get('auth_user');
        $cart = cart::where('customer_id', $auth['id'])->get();
        return response()->json($cart);
    }
    public function addToCart(Request $request)
    {
        $auth = $request->attributes->get('auth_user');
        $request->validate([
            'product_id' => 'required|integer',
            'quantity' => 'required|integer'
        ]);

        $product = Product::find($request->product_id);
        if (!$product) {
            return response()->json([
                'message' => 'Product not found'
            ]);
        }
        if (!$product) {
            return response()->json([
                'message' => 'Product not found'
            ]);
        }

        $cart = Cart::create([
            'customer_id' => $auth['id'],
            'product_id' => $product->id,
            'product_title' => $product->title,
            'product_price' => $product->discount_price ?? $product->price,
            'product_image' => $product->image,
            'quantity' => $request->quantity
        ]);
        return response()->json([
            'message' => 'Added to Cart',
            'cart' => $cart
        ]);
    }

    public function updateCart(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer'
        ]);
        $cart = Cart::find($id);
        if (!$cart) {
            return response()->json([
                'message' => 'Cart item not found'
            ]);
        }
        $cart->update([
            'quantity' => $request->quantity
        ]);
        return response()->json([
            'message' => 'Cart updated',
            'cart' => $cart
        ]);
    }

    public function deleteCart($id)
    {
        $cart = Cart::find($id);
        if (!$cart) {
            return response()->json([
                'message' => 'Cart item not found'
            ]);
        }
        $cart->delete();
        return response()->json([
            'message' => 'Item removed from cart'
        ]);
    }
}
