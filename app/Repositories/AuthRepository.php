<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\AuthRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthRepository implements AuthRepositoryInterface
{
    protected $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function register(array $data)
    {
        // Validate user type
        if ($data['user_type'] !== 'customer') {
            throw ValidationException::withMessages([
                'user_type' => ['Invalid user type for registration. Only customers can register.'],
            ]);
        }

        $user = $this->model->create([
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'user_type' => $data['user_type'],
        ]);

        // Assign role based on type
        $user->assignRole($data['user_type']);

        return $user;
    }

    public function login(array $credentials)
    {
        if (!Auth::attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $user = Auth::user();
        
        // Check if user is active based on their type
        if ($user->isServiceProvider() && !$user->serviceProvider?->is_active) {
            Auth::logout();
            throw ValidationException::withMessages([
                'email' => ['Your account is inactive. Please contact support.'],
            ]);
        }

        if ($user->isRealEstateOffice() && !$user->realEstateOffice?->is_active) {
            Auth::logout();
            throw ValidationException::withMessages([
                'email' => ['Your account is inactive. Please contact support.'],
            ]);
        }

        if ($user->isFinishingCompany() && !$user->finishingCompany?->is_active) {
            Auth::logout();
            throw ValidationException::withMessages([
                'email' => ['Your account is inactive. Please contact support.'],
            ]);
        }

        return [
            'user' => $user,
            'token' => $user->createToken('auth-token')->plainTextToken,
        ];
    }

    public function logout()
    {
        $user = Auth::user();
        if ($user) {
            $user->tokens()->delete();
        }
        Auth::logout();
        return true;
    }

    public function getAuthenticatedUser()
    {
        return Auth::user();
    }
} 