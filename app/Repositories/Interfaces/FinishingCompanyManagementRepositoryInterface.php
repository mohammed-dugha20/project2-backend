<?php

namespace App\Repositories\Interfaces;

interface FinishingCompanyManagementRepositoryInterface
{
    public function getCompanyProfile(int $userId);
    public function updateCompanyProfile(int $userId, array $data);
    public function toggleCompanyStatus(int $userId);
    public function getCompanyReviews(int $userId, array $filters = []);
    public function getFinishingRequests(int $userId, array $filters = []);
    public function getFinishingRequestDetails(int $requestId, int $userId);
    public function respondToRequest(int $requestId, int $userId, array $data);
    public function getRequestHistory(int $userId, array $filters = []);
    public function getCompanyAnalytics(int $userId);
    public function getCompanyPerformance(int $userId);
} 