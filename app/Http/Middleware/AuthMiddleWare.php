<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Firebase\JWT\Key;
use Symfony\Component\HttpFoundation\Response;

class AuthMiddleWare
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $authHeader = $request->header('Authorization');
        if (!$authHeader) {
            return response()->json([
                'message' => 'Token is not provided',
            ], 401);
        }
        $token = str_replace('Bearer ', '', $authHeader);
        $secretKey = env('SECRET_KEY');
        try {
            $decode = JWT::decode($token, new Key($secretKey, 'HS256'));
            if (!isset($decode->exp) || $decode->exp < time()) {
                return response()->json([
                    'message' => 'Token is expired',
                ], 401);
            }
            $request->attributes->set('auth_user', [
                'id' => $decode->id,
                'role' => $decode->role,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Token is invalid',
                'error' => $e->getMessage()
            ], 401);
        }
        return $next($request);
    }
}
