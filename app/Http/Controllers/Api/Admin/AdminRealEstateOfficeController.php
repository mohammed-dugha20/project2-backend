<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RealEstateOffice\StoreRealEstateOfficeRequest;
use App\Http\Requests\Admin\RealEstateOffice\UpdateRealEstateOfficeRequest;
use App\Services\Admin\AdminRealEstateOfficeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Resources\Admin\AdminRealEstateOfficeResource;

class AdminRealEstateOfficeController extends Controller
{
    protected $service;

    public function __construct(AdminRealEstateOfficeService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request): JsonResponse
    {
        $offices = $this->service->getAll($request->all());
        return response()->json(['success' => true, 'data' => AdminRealEstateOfficeResource::collection($offices)]);
    }

    public function show($id): JsonResponse
    {
        $office = $this->service->getById($id);
        if (!$office) {
            return response()->json(['success' => false, 'message' => 'Office not found'], 404);
        }
        return response()->json(['success' => true, 'data' => new AdminRealEstateOfficeResource($office)]);
    }

    public function store(StoreRealEstateOfficeRequest $request): JsonResponse
    {
        $office = $this->service->create($request->validated());
        return response()->json(['success' => true, 'data' => new AdminRealEstateOfficeResource($office), 'message' => 'Real estate office created successfully'], 201);
    }

    public function update(UpdateRealEstateOfficeRequest $request, $id): JsonResponse
    {
        $office = $this->service->update($id, $request->validated());
        if (!$office) {
            return response()->json(['success' => false, 'message' => 'Office not found'], 404);
        }
        return response()->json(['success' => true, 'data' => new AdminRealEstateOfficeResource($office), 'message' => 'Real estate office updated successfully']);
    }

    public function destroy($id): JsonResponse
    {
        $deleted = $this->service->delete($id);
        if (!$deleted) {
            return response()->json(['success' => false, 'message' => 'Office not found'], 404);
        }
        return response()->json(['success' => true, 'message' => 'Real estate office deleted successfully']);
    }

    public function toggleStatus($id): JsonResponse
    {
        $office = $this->service->toggleStatus($id);
        if (!$office) {
            return response()->json(['success' => false, 'message' => 'Office not found'], 404);
        }
        return response()->json(['success' => true, 'data' => new AdminRealEstateOfficeResource($office), 'message' => 'Office status updated successfully']);
    }

    public function reviews($id): JsonResponse
    {
        $reviews = $this->service->getReviews($id);
        return response()->json(['success' => true, 'data' => $reviews]);
    }

    public function documents($id): JsonResponse
    {
        $documents = $this->service->getDocuments($id);
        return response()->json(['success' => true, 'data' => $documents]);
    }

    public function properties($id): JsonResponse
    {
        $properties = $this->service->getProperties($id);
        return response()->json(['success' => true, 'data' => $properties]);
    }

    public function performance($id): JsonResponse
    {
        $performance = $this->service->getPerformance($id);
        return response()->json(['success' => true, 'data' => $performance]);
    }

    public function analytics($id): JsonResponse
    {
        $analytics = $this->service->getAnalytics($id);
        return response()->json(['success' => true, 'data' => $analytics]);
    }
} 