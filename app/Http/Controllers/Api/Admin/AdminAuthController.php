<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Services\AdminAuthService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AdminAuthController extends Controller
{
    protected $adminAuthService;

    public function __construct(AdminAuthService $adminAuthService)
    {
        $this->adminAuthService = $adminAuthService;
    }

    public function login(Request $request): JsonResponse
    {
        try {
            $result = $this->adminAuthService->login($request->all());
            return response()->json([
                'message' => 'Admin login successful',
                'admin' => $result['admin'],
                'role' => $result['user']['user_type'],
                'token' => $result['token']
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Admin login failed',
                'error' => $e->getMessage()
            ], 401);
        }
    }

    public function logout(): JsonResponse
    {
        $this->adminAuthService->logout();
        return response()->json([
            'message' => 'Admin successfully logged out'
        ]);
    }

    public function me(): JsonResponse
    {
        $admin = $this->adminAuthService->getAuthenticatedAdmin();
        return response()->json([
            'admin' => $admin
        ]);
    }

    public function createAdmin(Request $request): JsonResponse
    {
        try {
            $admin = $this->adminAuthService->createAdmin($request->all());
            return response()->json([
                'message' => 'Admin created successfully',
                'admin' => $admin
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create admin',
                'error' => $e->getMessage()
            ], 422);
        }
    }

    public function updateAdmin(Request $request, int $id): JsonResponse
    {
        try {
            $admin = $this->adminAuthService->updateAdmin($id, $request->all());
            return response()->json([
                'message' => 'Admin updated successfully',
                'admin' => $admin
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update admin',
                'error' => $e->getMessage()
            ], 422);
        }
    }

    public function deleteAdmin(int $id): JsonResponse
    {
        try {
            $this->adminAuthService->deleteAdmin($id);
            return response()->json([
                'message' => 'Admin deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete admin',
                'error' => $e->getMessage()
            ], 422);
        }
    }

    public function getAllAdmins(Request $request): JsonResponse
    {
        $admins = $this->adminAuthService->getAllAdmins($request->all());
        return response()->json($admins);
    }

    public function getAdminById(int $id): JsonResponse
    {
        $admin = $this->adminAuthService->getAdminById($id);
        return response()->json($admin);
    }

    public function toggleAdminStatus(int $id): JsonResponse
    {
        try {
            $admin = $this->adminAuthService->toggleAdminStatus($id);
            return response()->json([
                'message' => 'Admin status toggled successfully',
                'admin' => $admin
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to toggle admin status',
                'error' => $e->getMessage()
            ], 422);
        }
    }
} 