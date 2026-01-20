<?php

namespace App\Http\Controllers;

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
}
