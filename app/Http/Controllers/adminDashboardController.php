<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\order;
use App\Models\Product;
use Illuminate\Http\Request;

class adminDashboardController extends Controller
{
    public function boxesData()
    {
        return response()->json([
            'total_users' => Customer::count(),
            'total_products' => Product::count(),
            'total_orders' => order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'delivered_orders' => Order::where('status', 'delivered')->count()
        ]);
    }

    public function todayOrdersChart()
    {
        $todayOrders = Order::whereDate('created_at', today())->count();
        return response()->json([
            'today_orders' => $todayOrders
        ]);
    }

    public function last7DaysChart()
    {
        $data = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            $count = Order::whereDate('created_at', $date)->count();
            $data[] = [
                'date' => $date,
                'orders' => $count
            ];
        }

        return response()->json($data);
    }
}
