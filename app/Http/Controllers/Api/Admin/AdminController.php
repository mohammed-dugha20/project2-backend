<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Services\AdminService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AdminController extends Controller
{
    protected $adminService;

    public function __construct(AdminService $adminService)
    {
        $this->adminService = $adminService;
       
    }

    public function index(Request $request): JsonResponse
    {
        $users = $this->adminService->getAllUsers($request->all());
        return response()->json($users);
    }

    public function show(int $id): JsonResponse
    {
        $user = $this->adminService->getUserById($id);
        return response()->json($user);
    }

    public function store(Request $request): JsonResponse
    {
        try {
            
            $user = $this->adminService->createUser($request->all());
            return response()->json([
                'message' => 'User created successfully',
                'user' => $user
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create user',
                'errors' => $e->getMessage()
            ], 422);
        }
    }

    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $user = $this->adminService->updateUser($id, $request->all());
            return response()->json([
                'message' => 'User updated successfully',
                'user' => $user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update user',
                'errors' => $e->getMessage()
            ], 422);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $this->adminService->deleteUser($id);
            return response()->json([
                'message' => 'User deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete user',
                'errors' => $e->getMessage()
            ], 422);
        }
    }

    public function toggleStatus(int $id): JsonResponse
    {
        try {
            $user = $this->adminService->toggleUserStatus($id);
            return response()->json([
                'message' => 'User status toggled successfully',
                'user' => $user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to toggle user status',
                'errors' => $e->getMessage()
            ], 422);
        }
    }

    public function assignRole(Request $request, int $userId): JsonResponse
    {
        try {
            $user = $this->adminService->assignRole($userId, $request->role);
            return response()->json([
                'message' => 'Role assigned successfully',
                'user' => $user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to assign role',
                'errors' => $e->getMessage()
            ], 422);
        }
    }

    public function removeRole(Request $request, int $userId): JsonResponse
    {
        try {
            $user = $this->adminService->removeRole($userId, $request->role);
            return response()->json([
                'message' => 'Role removed successfully',
                'user' => $user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to remove role',
                'errors' => $e->getMessage()
            ], 422);
        }
    }

    public function assignPermission(Request $request, int $userId): JsonResponse
    {
        try {
            $user = $this->adminService->assignPermission($userId, $request->permission);
            return response()->json([
                'message' => 'Permission assigned successfully',
                'user' => $user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to assign permission',
                'errors' => $e->getMessage()
            ], 422);
        }
    }

    public function removePermission(Request $request, int $userId): JsonResponse
    {
        try {
            $user = $this->adminService->removePermission($userId, $request->permission);
            return response()->json([
                'message' => 'Permission removed successfully',
                'user' => $user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to remove permission',
                'errors' => $e->getMessage()
            ], 422);
        }
    }

    public function getRoles(): JsonResponse
    {
        $roles = $this->adminService->getRoles();
        return response()->json($roles);
    }

    public function getPermissions(): JsonResponse
    {
        $permissions = $this->adminService->getPermissions();
        return response()->json($permissions);
    }

    public function analytics(): JsonResponse
    {
        $analytics = [
            'total_users' => \App\Models\User::count(),
            'total_properties' => \App\Models\Property::count(),
            'total_real_estate_offices' => \App\Models\RealEstateOffice::count(),
            'total_finishing_companies' => \App\Models\FinishingCompany::count(),
            'active_properties' => \App\Models\Property::where('status_id', 1)->count(),
            'pending_requests' => \App\Models\PropertyRequest::where('status_id', 2)->count(),
        ];

        return response()->json(['analytics' => $analytics]);
    }

    public function reports(): JsonResponse
    {
        $reports = [
            'user_registration_trend' => \App\Models\User::selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->groupBy('date')
                ->orderBy('date', 'desc')
                ->limit(30)
                ->get(),
            'property_listings_by_type' => \App\Models\Property::selectRaw('type, COUNT(*) as count')
                ->groupBy('type')
                ->get(),
            'top_real_estate_offices' => \App\Models\RealEstateOffice::withCount('properties')
                ->orderBy('properties_count', 'desc')
                ->limit(10)
                ->get(),
        ];

        return response()->json(['reports' => $reports]);
    }

    public function settings(): JsonResponse
    {
        $settings = [
            'site_name' => config('app.name'),
            'maintenance_mode' => app()->isDownForMaintenance(),
            'registration_enabled' => config('auth.registration_enabled', true),
            'max_properties_per_office' => config('app.max_properties_per_office', 100),
        ];

        return response()->json(['settings' => $settings]);
    }

    public function updateSettings(Request $request): JsonResponse
    {
        try {
            // Update settings logic here
            return response()->json([
                'message' => 'Settings updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update settings',
                'errors' => $e->getMessage()
            ], 422);
        }
    }
} 