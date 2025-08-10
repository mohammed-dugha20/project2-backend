<?php

namespace App\Repositories\Admin\Interfaces;

use App\Models\Dispute;
use Illuminate\Pagination\LengthAwarePaginator;

interface AdminDisputeRepositoryInterface
{
    /**
     * List all disputes with optional filters
     *
     * @param array $filters
     * @return LengthAwarePaginator
     */
    public function listDisputes(array $filters = []): LengthAwarePaginator;

    /**
     * Get a dispute by its ID
     *
     * @param int $id
     * @return Dispute
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function getDisputeById(int $id): Dispute;

    /**
     * Update a dispute
     *
     * @param int $id
     * @param array $data
     * @return Dispute
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function updateDispute(int $id, array $data): Dispute;
}