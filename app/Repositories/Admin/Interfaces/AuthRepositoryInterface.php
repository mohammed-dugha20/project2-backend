<?php

namespace App\Repositories\Admin\Interfaces;

interface AuthRepositoryInterface
{
    public function register(array $data);
    public function login(array $credentials);
    public function logout();
    public function getAuthenticatedUser();
}