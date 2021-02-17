<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * Get a JWT via given credentials.
     *
     * @param Request $request
     * @param string $user
     * @return JsonResponse
     */
    public function login(Request $request, string $user): JsonResponse
    {
        $credentials = request(['email', 'password']);

        if (!$token = auth()->guard($user)->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token, $user);
    }

    /**
     * Get the token array structure.
     *
     * @param string $token
     * @param string $user
     * @return JsonResponse
     */
    protected function respondWithToken(string $token, string $user): JsonResponse
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth($user)->factory()->getTTL() * 60
        ]);
    }

    /**
     * Get the authenticated User.
     *
     * @param string $user
     * @return JsonResponse
     */
    public function me(string $user): JsonResponse
    {
        $user = auth($user)->user();
        return response()->json($user, $user ? 200 : 401);
    }
}
