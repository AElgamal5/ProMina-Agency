<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Http\Request;
use App\Services\AuthService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function __construct(private AuthService $authService)
    {
    }

    public function login(LoginRequest $request)
    {
        return response()->json([
            'message' => 'You have logged in successfully',
            'data' => $this->authService->login($request->email, $request->password)
        ], Response::HTTP_OK);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'You have logged out successfully',
            'data' => null
        ], Response::HTTP_OK);
    }

    public function register(RegisterRequest $request)
    {
        $this->authService->register($request->all());

        return response()->json([
            'message' => 'You have registered successfully',
            'data' => null
        ], Response::HTTP_CREATED);
    }
}
