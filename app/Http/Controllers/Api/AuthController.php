<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(Request $request): JsonResponse
    {
        try {
            $user = $this->authService->register($request->all());
            return response()->json([
                'message' => 'User registered successfully',
                'user' => $user
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Registration failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Registration failed',
                'error' => $e->getMessage()
            ], 422);
        }
    }

    public function login(Request $request)
    {
        try {
            $result = $this->authService->login($request->all());
            return response()->json([
                'message' => 'Login successful',
                'user' => $result['user'],
                'role' => $result['user']['user_type'],
                'token' => $result['token']
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Login failed',
                'errors' => $e->errors()
            ], 401);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Login failed',
                'error' => $e->getMessage()
            ], 401);
        }
    }

    public function logout(): JsonResponse
    {
        $this->authService->logout();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    public function me(): JsonResponse
    {
        $user = $this->authService->getAuthenticatedUser();
        return response()->json([
            'user' => $user
        ]);
    }

    

   

    
    
} 