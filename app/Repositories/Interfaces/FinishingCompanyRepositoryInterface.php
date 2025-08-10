<?php

namespace App\Repositories\Interfaces;

interface FinishingCompanyRepositoryInterface
{
    public function getAll(array $filters = []);
    public function getById(int $id);
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
    public function toggleStatus(int $id);
    public function getByUserId(int $userId);
    public function getServices(int $companyId);
    public function getWorkAreas(int $companyId);
    public function getPortfolio(int $companyId);
} 