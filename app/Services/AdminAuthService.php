<?php

namespace App\Services;

use App\Repositories\Admin\Interfaces\AdminAuthRepositoryInterface;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AdminAuthService
{
    protected $adminAuthRepository;

    public function __construct(AdminAuthRepositoryInterface $adminAuthRepository)
    {
        $this->adminAuthRepository = $adminAuthRepository;
    }

    public function login(array $credentials)
    {
        $validator = Validator::make($credentials, [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $this->adminAuthRepository->login($credentials);
    }

    public function logout()
    {
        return $this->adminAuthRepository->logout();
    }

    public function getAuthenticatedAdmin()
    {
        return $this->adminAuthRepository->getAuthenticatedAdmin();
    }

    public function createAdmin(array $data)
    {
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins',
            'username' => 'required|string|max:255|unique:admins',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8',
            'roles' => 'array',
            'roles.*' => 'string|exists:roles,name',
            'permissions' => 'array',
            'permissions.*' => 'string|exists:permissions,name',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $this->adminAuthRepository->createAdmin($data);
    }

    public function updateAdmin(int $id, array $data)
    {
        $validator = Validator::make($data, [
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:admins,email,' . $id,
            'username' => 'sometimes|required|string|max:255|unique:admins,username,' . $id,
            'phone' => 'nullable|string|max:20',
            'password' => 'sometimes|required|string|min:8',
            'roles' => 'array',
            'roles.*' => 'string|exists:roles,name',
            'permissions' => 'array',
            'permissions.*' => 'string|exists:permissions,name',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        try {
            return $this->adminAuthRepository->updateAdmin($id, $data);
        } catch (ModelNotFoundException $e) {
            throw new ValidationException(Validator::make([], []), 'Admin not found');
        }
    }

    public function deleteAdmin(int $id)
    {
        try {
            return $this->adminAuthRepository->deleteAdmin($id);
        } catch (ModelNotFoundException $e) {
            throw new ValidationException(Validator::make([], []), 'Admin not found');
        }
    }

    public function getAllAdmins(array $filters = [])
    {
        return $this->adminAuthRepository->getAllAdmins($filters);
    }

    public function getAdminById(int $id)
    {
        try {
            return $this->adminAuthRepository->getAdminById($id);
        } catch (ModelNotFoundException $e) {
            throw new ValidationException(Validator::make([], []), 'Admin not found');
        }
    }

    public function toggleAdminStatus(int $id)
    {
        try {
            return $this->adminAuthRepository->toggleAdminStatus($id);
        } catch (ModelNotFoundException $e) {
            throw new ValidationException(Validator::make([], []), 'Admin not found');
        }
    }
} 