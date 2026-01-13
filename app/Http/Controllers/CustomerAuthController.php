<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Firebase\JWT\JWT;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CustomerAuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
            'password' => 'required|string|min:6',
        ]);

        $customer = Customer::where('email', $request->email)->first();
        if ($customer) {
            return response()->json([
                'message' => 'Email already exists'
            ]);
        }

        $customer = Customer::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        $secretKey = env('SECRET_KEY');
        $payload = [
            'id' => $customer->id,
            'name' => $customer->name,
            'email' => $customer->email,
            'phone' => $customer->phone,
            'role' => 'customer',
            'iat' => time(),
            'exp' => time() + 3600
        ];
        $jwt = JWT::encode($payload, $secretKey, 'HS256');
        return response()->json([
            'message' => 'Customer registered successfully',
            'customer' => $customer,
            'token' => $jwt,
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);
        $customer = Customer::where('email', $request->email)->first();
        if (!$customer) {
            return response()->json([
                'message' => 'Email does not exist'
            ]);
        }
        if (!Hash::check($request->password, $customer->password)) {
            return response()->json([
                'message' => 'Password is incorrect',
            ]);
        }
        $secretKey = env('SECRET_KEY');
        $payload = [
            'id' => $customer->id,
            'email' => $customer->email,
            'role' => 'customer',
            'iat' => time(),
            'exp' => time() + 3600,
        ];
        $jwt = JWT::encode($payload, $secretKey, 'HS256');

        return response()->json([
            'message' => 'Customer logged in successfully',
            'customer' => $customer,
            'token' => $jwt,
        ]);
    }

    public function me(Request $request)
    {
        $auth = $request->attributes->get('auth_user');
        $customer = Customer::find($auth['id']);
        if (!$customer) {
            return response()->json([
                'message' => 'Customer not found',
            ]);
        }
        return response()->json([
            'customer' => $customer,
        ]);
    }

    public function updateProfile(Request $request)
    {
        $auth = $request->attributes->get('auth_user');
        $customer = Customer::find($auth['id']);
        if (!$customer) {
            return response()->json([
                'message' => 'Customer not found',
            ]);
        }
        $customer->update($request->only([
            'name',
            'phone'
        ]));
        return response()->json([
            'message' => 'profile updated successfully',
            'customer' => $customer,
        ]);
    }
}
