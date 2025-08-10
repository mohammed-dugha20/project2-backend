<?php

namespace App\Repositories\Interfaces;

interface AdminRepositoryInterface
{
    public function getAllUsers(array $filters = []);
    public function getUserById(int $id);
    public function createUser(array $data);
    public function updateUser(int $id, array $data);
    public function deleteUser(int $id);
    public function toggleUserStatus(int $id);
    public function assignRole(int $userId, string $role);
    public function removeRole(int $userId, string $role);
    public function assignPermission(int $userId, string $permission);
    public function removePermission(int $userId, string $permission);
    public function getRoles();
    public function getPermissions();
} 