<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Dispute\UpdateDisputeRequest;
use App\Http\Resources\Admin\DisputeResource;
use App\Services\AdminDisputeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminDisputeController extends Controller
{
    protected $service;

    public function __construct(AdminDisputeService $service)
    {
        $this->service = $service;
       
    }

    /**
     * List all disputes (with filters)
     */
    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['company_type', 'company_id', 'status', 'date_from', 'date_to', 'search', 'per_page']);
        $disputes = $this->service->listDisputes($filters);
        return response()->json([
            'success' => true,
            'data' => DisputeResource::collection($disputes),
            'pagination' => [
                'current_page' => $disputes->currentPage(),
                'last_page' => $disputes->lastPage(),
                'per_page' => $disputes->perPage(),
                'total' => $disputes->total(),
            ],
            'message' => 'Disputes retrieved successfully'
        ]);
    }

    /**
     * View a specific dispute
     */
    public function show(int $id): JsonResponse
    {
        $dispute = $this->service->getDisputeById($id);
        return response()->json([
            'success' => true,
            'data' => new DisputeResource($dispute),
            'message' => 'Dispute retrieved successfully'
        ]);
    }

    /**
     * Update/resolve a dispute
     */
    public function update(UpdateDisputeRequest $request, int $id): JsonResponse
    {
        $data = $request->validated();
        $data['resolved_by'] = Auth::guard('admin')->id();
        if (in_array($data['status'], ['resolved', 'closed'])) {
            $data['resolved_at'] = now();
        }
        $dispute = $this->service->updateDispute($id, $data);
        return response()->json([
            'success' => true,
            'data' => new DisputeResource($dispute),
            'message' => 'Dispute updated successfully'
        ]);
    }
} 