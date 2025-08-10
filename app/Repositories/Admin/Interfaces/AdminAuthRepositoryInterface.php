<?php

namespace App\Repositories\Admin\Interfaces;

interface AdminAuthRepositoryInterface
{
    public function login(array $credentials);
    public function logout();
    public function getAuthenticatedAdmin();
    public function createAdmin(array $data);
    public function updateAdmin(int $id, array $data);
    public function deleteAdmin(int $id);
    public function getAllAdmins(array $filters = []);
    public function getAdminById(int $id);
    public function toggleAdminStatus(int $id);
}