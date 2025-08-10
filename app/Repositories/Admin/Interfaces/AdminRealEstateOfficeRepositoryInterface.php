<?php

namespace App\Repositories\Admin\Interfaces;

interface AdminRealEstateOfficeRepositoryInterface
{
    public function getAll(array $filters = []);
    public function getById(int $id);
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
    public function toggleStatus(int $id);
    public function getReviews(int $officeId);
    public function getDocuments(int $officeId);
    public function getProperties(int $officeId);
    public function addDocument(int $officeId, array $documentData);
    public function removeDocument(int $documentId);
    public function getPerformance(int $officeId);
    public function getAnalytics(int $officeId);
} 