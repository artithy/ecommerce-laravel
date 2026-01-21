<?php

namespace App\Http\Controllers;

use App\Models\cart;
use App\Models\order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function getCustomerOrders(Request $request)
    {
        $auth = $request->attributes->get('auth_user');
        $order = order::where('customer_id', $auth['id'])->get();
        return response()->json($order);
    }

    public function getAllOrders()
    {
        return response()->json(Order::all());
    }
    public function getRecentOrders()
    {
        $orders = Order::latest()->take(5)->get();
        return response()->json($orders);
    }
    public function updateOrderStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string'
        ]);
        $order = Order::find($id);
        if (!$order) {
            return response()->json([
                'message' => 'Order not found'
            ]);
        }
        $order->update([
            'status' => $request->status
        ]);
        return response()->json([
            'message' => 'Order status updated',
            'order' => $order
        ]);
    }

    public function placeOrder(Request $request)
    {
        $auth = $request->attributes->get('auth_user');

        $request->validate([
            'name'    => 'required|string',
            'phone'   => 'required|string',
            'address' => 'required|string',
        ]);

        $cartItems = Cart::where('customer_id', $auth['id'])->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['message' => 'Cart is empty']);
        }

        $total = 0;
        foreach ($cartItems as $item) {
            $total += $item->product_price * $item->quantity;
        }

        $order = Order::create([
            'customer_id'      => $auth['id'],
            'customer_name'    => $request->name,
            'customer_phone'   => $request->phone,
            'customer_address' => $request->address,
            'items'            => json_encode($cartItems),
            'total_price'      => $total,
            'status'           => 'pending'
        ]);

        cart::where('customer_id', $auth['id'])->delete();

        return response()->json([
            'message' => 'Order placed successfully',
            'order'   => $order
        ]);
    }
}
