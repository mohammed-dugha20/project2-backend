<?php

namespace App\Repositories\Admin;

use App\Models\Admin;
use App\Repositories\Admin\Interfaces\AdminAuthRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AdminAuthRepository implements AdminAuthRepositoryInterface
{
    protected $model;

    public function __construct(Admin $model)
    {
        $this->model = $model;
    }

    public function login(array $credentials)
    {
        $admin = $this->model->where('email', $credentials['email'])->first();

        if (!$admin || !Hash::check($credentials['password'], $admin->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        if (!$admin->isActive()) {
            throw ValidationException::withMessages([
                'email' => ['Your account is inactive. Please contact support.'],
            ]);
        }

        // Update last login
        $admin->updateLastLogin();

        return [
            'admin' => $admin,
            'token' => $admin->createToken('admin-token', ['admin'])->plainTextToken,
        ];
    }

    public function logout()
    {
        $admin = Auth::user();
        if ($admin) {
            $admin->tokens()->delete();
        }
        return true;
    }

    public function getAuthenticatedAdmin()
    {
        return Auth::user();
    }

    public function createAdmin(array $data)
    {
        $admin = $this->model->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'username' => $data['username'],
            'phone' => $data['phone'] ?? null,
            'password' => Hash::make($data['password']),
            'is_active' => true,
        ]);

        if (isset($data['roles'])) {
            $admin->syncRoles($data['roles']);
        }

        if (isset($data['permissions'])) {
            $admin->syncPermissions($data['permissions']);
        }

        return $admin;
    }

    public function updateAdmin(int $id, array $data)
    {
        $admin = $this->getAdminById($id);

        $updateData = array_filter([
            'name' => $data['name'] ?? null,
            'email' => $data['email'] ?? null,
            'username' => $data['username'] ?? null,
            'phone' => $data['phone'] ?? null,
        ]);

        if (isset($data['password'])) {
            $updateData['password'] = Hash::make($data['password']);
        }

        $admin->update($updateData);

        if (isset($data['roles'])) {
            $admin->syncRoles($data['roles']);
        }

        if (isset($data['permissions'])) {
            $admin->syncPermissions($data['permissions']);
        }

        return $admin;
    }

    public function deleteAdmin(int $id)
    {
        $admin = $this->getAdminById($id);
        return $admin->delete();
    }

    public function getAllAdmins(array $filters = [])
    {
        $query = $this->model->query();

        if (isset($filters['search'])) {
            $query->where(function (Builder $query) use ($filters) {
                $query->where('name', 'like', "%{$filters['search']}%")
                    ->orWhere('email', 'like', "%{$filters['search']}%")
                    ->orWhere('username', 'like', "%{$filters['search']}%");
            });
        }

        if (isset($filters['is_active'])) {
            $query->where('is_active', $filters['is_active']);
        }

        return $query->paginate($filters['per_page'] ?? 15);
    }

    public function getAdminById(int $id)
    {
        $admin = $this->model->find($id);
        if (!$admin) {
            throw new ModelNotFoundException('Admin not found');
        }
        return $admin;
    }

    public function toggleAdminStatus(int $id)
    {
        $admin = $this->getAdminById($id);
        $admin->update(['is_active' => !$admin->is_active]);
        return $admin;
    }
}