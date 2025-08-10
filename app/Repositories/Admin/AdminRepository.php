<?php

namespace App\Repositories\Admin;

use App\Models\User;
use App\Repositories\Admin\Interfaces\AdminRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AdminRepository implements AdminRepositoryInterface
{
    protected $model;
    protected $role;
    protected $permission;

    public function __construct(User $model, Role $role, Permission $permission)
    {
        $this->model = $model;
        $this->role = $role;
        $this->permission = $permission;
    }

    public function getAllUsers(array $filters = [])
    {
        $query = $this->model->query();

        if (isset($filters['user_type'])) {
            $query->where('user_type', $filters['user_type']);
        }

        if (isset($filters['search'])) {
            $query->where(function (Builder $query) use ($filters) {
                $query->where('username', 'like', "%{$filters['search']}%")
                    ->orWhere('email', 'like', "%{$filters['search']}%");
            });
        }

        return $query->paginate($filters['per_page'] ?? 15);
    }

    public function getUserById(int $id)
    {
        $user = $this->model->find($id);
        if (!$user) {
            throw new ModelNotFoundException('User not found');
        }
        return $user;
    }

    public function createUser(array $data)
    {
        $user = $this->model->create([
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'user_type' => $data['user_type'],
        ]);

        if (isset($data['roles'])) {
            $user->syncRoles($data['roles']);
        }

        if (isset($data['permissions'])) {
            $user->syncPermissions($data['permissions']);
        }

        return $user;
    }

    public function updateUser(int $id, array $data)
    {
        $user = $this->getUserById($id);

        $updateData = array_filter([
            'username' => $data['username'] ?? null,
            'email' => $data['email'] ?? null,
            'user_type' => $data['user_type'] ?? null,
        ]);

        if (isset($data['password'])) {
            $updateData['password'] = Hash::make($data['password']);
        }

        $user->update($updateData);

        if (isset($data['roles'])) {
            $user->syncRoles($data['roles']);
        }

        if (isset($data['permissions'])) {
            $user->syncPermissions($data['permissions']);
        }

        return $user;
    }

    public function deleteUser(int $id)
    {
        $user = $this->getUserById($id);
        return $user->delete();
    }

    public function toggleUserStatus(int $id)
    {
        $user = $this->getUserById($id);
        
        switch ($user->user_type) {
            case 'real_estate_office':
                $user->realEstateOffice->update(['is_active' => !$user->realEstateOffice->is_active]);
                break;
            case 'finishing_company':
                $user->finishingCompany->update(['is_active' => !$user->finishingCompany->is_active]);
                break;
        }

        return $user;
    }

    public function assignRole(int $userId, string $role)
    {
        $user = $this->getUserById($userId);
        $user->assignRole($role);
        return $user;
    }

    public function removeRole(int $userId, string $role)
    {
        $user = $this->getUserById($userId);
        $user->removeRole($role);
        return $user;
    }

    public function assignPermission(int $userId, string $permission)
    {
        $user = $this->getUserById($userId);
        $user->givePermissionTo($permission);
        return $user;
    }

    public function removePermission(int $userId, string $permission)
    {
        $user = $this->getUserById($userId);
        $user->revokePermissionTo($permission);
        return $user;
    }

    public function getRoles()
    {
        return $this->role->all();
    }

    public function getPermissions()
    {
        return $this->permission->all();
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
} 