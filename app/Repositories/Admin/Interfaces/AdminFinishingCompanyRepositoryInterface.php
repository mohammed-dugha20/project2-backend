<?php

namespace App\Repositories\Admin\Interfaces;

interface AdminFinishingCompanyRepositoryInterface
{
    public function getAllFinishingCompanies(array $filters = []);
    public function getFinishingCompanyById(int $id);
    public function createFinishingCompany(array $data);
    public function updateFinishingCompany(int $id, array $data);
    public function deleteFinishingCompany(int $id);
    public function toggleFinishingCompanyStatus(int $id);
    public function getFinishingCompanyServices(int $id);
    public function getFinishingCompanyWorkAreas(int $id);
    public function getFinishingCompanyPortfolio(int $id);
    public function getFinishingCompanyRequests(int $id, array $filters = []);
    public function getFinishingCompanyReviews(int $id, array $filters = []);
    public function getFinishingCompanyAnalytics(int $id);
    public function getFinishingCompanyPerformance(int $id);
    public function archiveFinishingCompanyProjects(int $id);
}