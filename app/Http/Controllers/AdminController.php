<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        $admin = Admin::where('email', $request->email)->first();
        if ($admin) {
            return response()->json([
                'message' => 'Email already exists',
            ]);
        }

        $admin = Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $payload = [
            'id' => $admin->id,
            'email' => $admin->email,
            'role' => 'admin',
            'iat' => time(),
            'exp' => time() + 3600
        ];
        $token = JWT::encode($payload, env('SECRET_KEY'), 'HS256');
        return response()->json([
            'message' => 'Admin registered successfully',
            'admin' => $admin,
            'token' => $token,
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $admin = Admin::where('email', $request->email)->first();

        if (!$admin) {
            return response()->json([
                'message' => 'Email doesnot exist'
            ]);
        }

        if (!Hash::check($request->password, $admin->password)) {
            return response([
                'message' => ' password is incorrect'
            ]);
        }

        $payload = [
            'id' => $admin->id,
            'email' => $admin->email,
            'role' => 'admin',
            'iat' => time(),
            'exp' => time() + 3600,
        ];

        $token = JWT::encode($payload, env('SECRET_KEY'), 'HS256');

        return response()->json([
            'message' => 'Login successfully',
            'admin' => $admin,
            'token' => $token
        ]);
    }
}
