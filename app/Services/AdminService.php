<?php

namespace App\Services;

use App\Repositories\Admin\Interfaces\AdminRepositoryInterface;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AdminService
{
    protected $adminRepository;

    public function __construct(AdminRepositoryInterface $adminRepository)
    {
        $this->adminRepository = $adminRepository;
    }

    public function getAllUsers(array $filters = [])
    {
        return $this->adminRepository->getAllUsers($filters);
    }

    public function getUserById(int $id)
    {
        try {
            return $this->adminRepository->getUserById($id);
        } catch (ModelNotFoundException $e) {
            throw new ValidationException(Validator::make([], []), 'User not found');
        }
    }

    public function createUser(array $data)
    {
        $validator = Validator::make($data, [
            'username' => 'required|string|max:255|unique:users',
            'name' => 'required|string',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'user_type' => 'required|string|in:customer,real_estate_office,finishing_company,platform_admin',
            'roles' => 'array',
            'roles.*' => 'string|exists:roles,name',
            'permissions' => 'array',
            'permissions.*' => 'string|exists:permissions,name',
        ]);

        

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        
        return $this->adminRepository->createUser($data);
    }

    public function updateUser(int $id, array $data)
    {
        $validator = Validator::make($data, [
            'username' => 'sometimes|required|string|max:255|unique:users,username,' . $id,
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'sometimes|required|string|min:8',
            'user_type' => 'sometimes|required|string|in:customer,real_estate_office,finishing_company,platform_admin',
            'roles' => 'array',
            'roles.*' => 'string|exists:roles,name',
            'permissions' => 'array',
            'permissions.*' => 'string|exists:permissions,name',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        try {
            return $this->adminRepository->updateUser($id, $data);
        } catch (ModelNotFoundException $e) {
            throw new ValidationException(Validator::make([], []), 'User not found');
        }
    }

    public function deleteUser(int $id)
    {
        try {
            return $this->adminRepository->deleteUser($id);
        } catch (ModelNotFoundException $e) {
            throw new ValidationException(Validator::make([], []), 'User not found');
        }
    }

    public function toggleUserStatus(int $id)
    {
        try {
            return $this->adminRepository->toggleUserStatus($id);
        } catch (ModelNotFoundException $e) {
            throw new ValidationException(Validator::make([], []), 'User not found');
        }
    }

    public function assignRole(int $userId, string $role)
    {
        $validator = Validator::make(['role' => $role], [
            'role' => 'required|string|exists:roles,name',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        try {
            return $this->adminRepository->assignRole($userId, $role);
        } catch (ModelNotFoundException $e) {
            throw new ValidationException(Validator::make([], []), 'User not found');
        }
    }

    public function removeRole(int $userId, string $role)
    {
        $validator = Validator::make(['role' => $role], [
            'role' => 'required|string|exists:roles,name',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        try {
            return $this->adminRepository->removeRole($userId, $role);
        } catch (ModelNotFoundException $e) {
            throw new ValidationException(Validator::make([], []), 'User not found');
        }
    }

    public function assignPermission(int $userId, string $permission)
    {
        $validator = Validator::make(['permission' => $permission], [
            'permission' => 'required|string|exists:permissions,name',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        try {
            return $this->adminRepository->assignPermission($userId, $permission);
        } catch (ModelNotFoundException $e) {
            throw new ValidationException(Validator::make([], []), 'User not found');
        }
    }

    public function removePermission(int $userId, string $permission)
    {
        $validator = Validator::make(['permission' => $permission], [
            'permission' => 'required|string|exists:permissions,name',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        try {
            return $this->adminRepository->removePermission($userId, $permission);
        } catch (ModelNotFoundException $e) {
            throw new ValidationException(Validator::make([], []), 'User not found');
        }
    }

    public function getRoles()
    {
        return $this->adminRepository->getRoles();
    }

    public function getPermissions()
    {
        return $this->adminRepository->getPermissions();
    }
} 