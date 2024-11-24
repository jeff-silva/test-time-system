<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiError;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class AuthController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth:api', except: ['login']),
        ];
    }

    protected function respondWithToken($token)
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ];
    }

    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            throw new ApiError(401, 'Unauthorized');
        }

        return $this->respondWithToken($token);
    }

    public function me()
    {
        $user = auth()->user();
        if (!$user) throw new ApiError(401, 'Unauthorized');
        return $user;
    }

    public function logout()
    {
        auth()->logout();
        return ['message' => 'Successfully logged out'];
    }

    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }
}
