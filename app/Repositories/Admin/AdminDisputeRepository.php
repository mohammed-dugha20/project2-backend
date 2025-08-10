<?php

namespace App\Repositories\Admin;

use App\Models\Dispute;
use App\Repositories\Admin\Interfaces\AdminDisputeRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class AdminDisputeRepository implements AdminDisputeRepositoryInterface
{
    protected $dispute;

    public function __construct(Dispute $dispute)
    {
        $this->dispute = $dispute;
    }

    public function listDisputes(array $filters = []): LengthAwarePaginator
    {
        $query = $this->dispute->with(['customer', 'finishingCompany', 'realEstateOffice', 'finishingRequest', 'resolvedBy']);

        if (isset($filters['company_type'])) {
            $query->where('company_type', $filters['company_type']);
        }
        if (isset($filters['company_id'])) {
            $query->where('company_id', $filters['company_id']);
        }
        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        if (isset($filters['date_from'])) {
            $query->where('created_at', '>=', $filters['date_from']);
        }
        if (isset($filters['date_to'])) {
            $query->where('created_at', '<=', $filters['date_to']);
        }
        if (isset($filters['search'])) {
            $query->where(function (Builder $q) use ($filters) {
                $q->where('subject', 'like', "%{$filters['search']}%")
                    ->orWhere('description', 'like', "%{$filters['search']}%");
            });
        }
        $query->orderBy('created_at', 'desc');
        return $query->paginate($filters['per_page'] ?? 15);
    }

    public function getDisputeById(int $id): Dispute
    {
        $dispute = $this->dispute->with(['customer', 'finishingCompany', 'realEstateOffice', 'finishingRequest', 'resolvedBy'])->find($id);
        if (!$dispute) {
            throw new ModelNotFoundException('Dispute not found');
        }
        return $dispute;
    }

    public function updateDispute(int $id, array $data): Dispute
    {
        $dispute = $this->dispute->find($id);
        if (!$dispute) {
            throw new ModelNotFoundException('Dispute not found');
        }
        return DB::transaction(function () use ($dispute, $data) {
            $updateData = array_filter([
                'status' => $data['status'] ?? null,
                'resolution_notes' => $data['resolution_notes'] ?? null,
                'resolved_by' => $data['resolved_by'] ?? null,
                'resolved_at' => $data['resolved_at'] ?? null,
            ]);
            $dispute->update($updateData);
            return $dispute->fresh(['customer', 'finishingCompany', 'realEstateOffice', 'finishingRequest', 'resolvedBy']);
        });
    }
}