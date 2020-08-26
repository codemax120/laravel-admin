<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Requests\LoginRequest;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        $credentials = request(['email', 'password']);
        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }
        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        $user = auth('api')->user();
        if (!$user) {
            return response()->json(['user_not_found'], Response::HTTP_NOT_FOUND);
        } else {
            return response()->json(compact('user'), Response::HTTP_ACCEPTED);
        }
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        $user = auth('api')->user();
        if (!$user) {
            return response()->json(['user_not_found'], Response::HTTP_NOT_FOUND);
        } else {
            auth('api')->logout();
            return response()->json(['message' => 'Successfully logged out'], Response::HTTP_OK);
        }
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        $user = auth('api')->user();
        if (!$user) {
            return response()->json(['user_not_found'], Response::HTTP_NOT_FOUND);
        } else {
            $refreshToken = auth('api')->refresh();
            return $this->respondWithToken(compact('refreshToken'), Response::HTTP_CREATED);
        }
    }

    /**
     * Get the token array structure.
     *
     * @param string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        $response = [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 1
        ];
        return response()->json(compact('response'), Response::HTTP_ACCEPTED);
    }


    public function guard()
    {
        return Auth::Guard('api');
    }
}
