<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\PropertyController;
use App\Http\Controllers\Api\OfficeController;
use App\Http\Controllers\Api\RealEstateOfficeController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Admin\AdminController;
use App\Http\Controllers\Api\FinishingCompanyController;
use App\Http\Controllers\Api\Admin\AdminAuthController;
use App\Http\Controllers\Api\Admin\AdminDisputeController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Public Real Estate Office routes
Route::prefix('real-estate-offices')->group(function () {
    Route::get('/', [RealEstateOfficeController::class, 'publicIndex']);
    Route::get('/{id}', [RealEstateOfficeController::class, 'publicShow']);
});

// Admin Authentication Routes
Route::prefix('admin/auth')->group(function () {
    Route::post('/login', [AdminAuthController::class, 'login']);
    Route::middleware(['auth:sanctum', 'admin'])->group(function () {
        Route::post('/logout', [AdminAuthController::class, 'logout']);
        Route::get('/me', [AdminAuthController::class, 'me']);
    });
});

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // User authentication
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    // Real Estate Office routes
    Route::prefix('real-estate-office')->middleware('real_estate_office')->group(function () {
        Route::get('/profile', [RealEstateOfficeController::class, 'profile']);
        Route::put('/profile', [RealEstateOfficeController::class, 'updateProfile']);
        Route::post('/documents', [RealEstateOfficeController::class, 'uploadDocument']);
        Route::delete('/documents/{documentId}', [RealEstateOfficeController::class, 'removeDocument']);
        Route::get('/properties', [RealEstateOfficeController::class, 'properties']);
        Route::get('/analytics', [RealEstateOfficeController::class, 'analytics']);
        Route::get('/reviews', [RealEstateOfficeController::class, 'reviews']);
    });

    // Admin Real Estate Office Management (for platform admins)
    Route::prefix('admin/real-estate-offices')->middleware('platform_admin')->group(function () {
        Route::get('/', [RealEstateOfficeController::class, 'index']);
        Route::get('/{id}', [RealEstateOfficeController::class, 'show']);
        Route::post('/', [RealEstateOfficeController::class, 'store']);
        Route::put('/{id}', [RealEstateOfficeController::class, 'update']);
        Route::patch('/{id}/toggle-status', [RealEstateOfficeController::class, 'toggleStatus']);
        Route::get('/{id}/reviews', [RealEstateOfficeController::class, 'adminReviews']);
        Route::get('/{id}/documents', [RealEstateOfficeController::class, 'adminDocuments']);
    });

    // Finishing Company Management Routes
    Route::prefix('finishing-company')->middleware(['finishing_company'])->group(function () {
        // Profile Management
        Route::get('/profile', [FinishingCompanyController::class, 'getProfile']);
        Route::put('/profile', [FinishingCompanyController::class, 'updateProfile']);
        Route::patch('/toggle-status', [FinishingCompanyController::class, 'toggleStatus']);

        // Reviews and Performance
        Route::get('/reviews', [FinishingCompanyController::class, 'getReviews']);
        Route::get('/analytics', [FinishingCompanyController::class, 'getAnalytics']);
        Route::get('/performance', [FinishingCompanyController::class, 'getPerformance']);

        // Finishing Requests Management
        Route::get('/requests', [FinishingCompanyController::class, 'getRequests']);
        Route::get('/requests/{requestId}', [FinishingCompanyController::class, 'getRequestDetails']);
        Route::post('/requests/{requestId}/respond', [FinishingCompanyController::class, 'respondToRequest']);

        // Request History and Analysis
        Route::get('/history', [FinishingCompanyController::class, 'getRequestHistory']);
    });

    // Properties routes (accessible by all authenticated users)
    Route::prefix('properties')->group(function () {
        Route::get('/', [PropertyController::class, 'index']);
        Route::get('/{property}', [PropertyController::class, 'show']);
        Route::get('/{property}/images', [PropertyController::class, 'images']);
        Route::get('/{property}/attachments', [PropertyController::class, 'attachments']);

        // Property management (Real Estate Offices only)
        Route::middleware('real_estate_office')->group(function () {
            Route::post('/', [PropertyController::class, 'store']);
            Route::put('/{property}', [PropertyController::class, 'update']);
            Route::delete('/{property}', [PropertyController::class, 'destroy']);
            Route::patch('/{property}/status', [PropertyController::class, 'updateStatus']);
            Route::post('/{property}/images', [PropertyController::class, 'uploadImages']);
            Route::post('/{property}/attachments', [PropertyController::class, 'uploadAttachments']);
            Route::delete('{property}/images/{image}', [PropertyController::class, 'deleteImage']);
            Route::delete('{property}/attachments/{attachment}', [PropertyController::class, 'deleteAttachment']);
        });
    });

    // Dispute Management
    Route::prefix('disputes')->group(function () {
        Route::get('/', [AdminDisputeController::class, 'index']);
        Route::get('/{id}', [AdminDisputeController::class, 'show']);
        Route::put('/{id}', [AdminDisputeController::class, 'update']);
    });
});

// Admin Management Routes (Admin guard)
Route::prefix('admin')->middleware(['auth:sanctum', 'admin'])->group(function () {
    // User Management
    Route::prefix('users')->group(function () {
        Route::get('/', [AdminController::class, 'index']);
        Route::get('/{id}', [AdminController::class, 'show']);
        Route::post('/', [AdminController::class, 'store']);
        Route::put('/{id}', [AdminController::class, 'update']);
        Route::delete('/{id}', [AdminController::class, 'destroy']);
        Route::patch('/{id}/toggle-status', [AdminController::class, 'toggleStatus']);

        // Role management
        Route::post('/{userId}/roles', [AdminController::class, 'assignRole']);
        Route::delete('/{userId}/roles', [AdminController::class, 'removeRole']);

        // Permission management
        Route::post('/{userId}/permissions', [AdminController::class, 'assignPermission']);
        Route::delete('/{userId}/permissions', [AdminController::class, 'removePermission']);
    });

    // Real Estate Office Management
    Route::prefix('real-estate-offices')->group(function () {
        Route::get('/', [\App\Http\Controllers\Api\Admin\AdminRealEstateOfficeController::class, 'index']);
        Route::get('/{id}', [\App\Http\Controllers\Api\Admin\AdminRealEstateOfficeController::class, 'show']);
        Route::post('/', [\App\Http\Controllers\Api\Admin\AdminRealEstateOfficeController::class, 'store']);
        Route::put('/{id}', [\App\Http\Controllers\Api\Admin\AdminRealEstateOfficeController::class, 'update']);
        Route::delete('/{id}', [\App\Http\Controllers\Api\Admin\AdminRealEstateOfficeController::class, 'destroy']);
        Route::patch('/{id}/toggle-status', [\App\Http\Controllers\Api\Admin\AdminRealEstateOfficeController::class, 'toggleStatus']);
        Route::get('/{id}/reviews', [\App\Http\Controllers\Api\Admin\AdminRealEstateOfficeController::class, 'reviews']);
        Route::get('/{id}/documents', [\App\Http\Controllers\Api\Admin\AdminRealEstateOfficeController::class, 'documents']);
        Route::get('/{id}/properties', [\App\Http\Controllers\Api\Admin\AdminRealEstateOfficeController::class, 'properties']);
        Route::get('/{id}/performance', [\App\Http\Controllers\Api\Admin\AdminRealEstateOfficeController::class, 'performance']);
        Route::get('/{id}/analytics', [\App\Http\Controllers\Api\Admin\AdminRealEstateOfficeController::class, 'analytics']);
    });

    // Finishing Company Management
    Route::prefix('finishing-companies')->group(function () {
        Route::get('/', [FinishingCompanyController::class, 'index']);
        Route::get('/{id}', [FinishingCompanyController::class, 'show']);
        Route::post('/', [FinishingCompanyController::class, 'store']);
        Route::put('/{id}', [FinishingCompanyController::class, 'update']);
        Route::delete('/{id}', [FinishingCompanyController::class, 'destroy']);
        Route::patch('/{id}/toggle-status', [FinishingCompanyController::class, 'adminToggleStatus']);
        Route::get('/{id}/services', [FinishingCompanyController::class, 'getServices']);
        Route::get('/{id}/work-areas', [FinishingCompanyController::class, 'getWorkAreas']);
        Route::get('/{id}/portfolio', [FinishingCompanyController::class, 'getPortfolio']);
        Route::get('/{id}/requests', [FinishingCompanyController::class, 'adminRequests']);
        Route::get('/{id}/reviews', [FinishingCompanyController::class, 'adminReviews']);
        Route::get('/{id}/analytics', [FinishingCompanyController::class, 'adminAnalytics']);
        Route::get('/{id}/performance', [FinishingCompanyController::class, 'adminPerformance']);
    });

    // System Management
    Route::prefix('system')->group(function () {
        Route::get('/roles', [AdminController::class, 'getRoles']);
        Route::get('/permissions', [AdminController::class, 'getPermissions']);
        Route::get('/analytics', [AdminController::class, 'analytics']);
        Route::get('/reports', [AdminController::class, 'reports']);
        Route::get('/settings', [AdminController::class, 'settings']);
        Route::put('/settings', [AdminController::class, 'updateSettings']);
    });
});

// Super Admin Management Routes (Super Admin only)
Route::prefix('admin/management')->middleware(['auth:sanctum', 'admin', 'platform_admin'])->group(function () {
    Route::get('/admins', [AdminAuthController::class, 'getAllAdmins']);
    Route::get('/admins/{id}', [AdminAuthController::class, 'getAdminById']);
    Route::post('/admins', [AdminAuthController::class, 'createAdmin']);
    Route::put('/admins/{id}', [AdminAuthController::class, 'updateAdmin']);
    Route::delete('/admins/{id}', [AdminAuthController::class, 'deleteAdmin']);
    Route::patch('/admins/{id}/toggle-status', [AdminAuthController::class, 'toggleAdminStatus']);
});

// Public API routes (no authentication required)
Route::prefix('public')->group(function () {
    Route::get('/properties', [PropertyController::class, 'publicIndex']);
    Route::get('/properties/{property}', [PropertyController::class, 'publicShow']);
    Route::get('/real-estate-offices', [RealEstateOfficeController::class, 'publicIndex']);
    Route::get('/real-estate-offices/{id}', [RealEstateOfficeController::class, 'publicShow']);
    Route::get('/finishing-companies', [FinishingCompanyController::class, 'publicIndex']);
    Route::get('/finishing-companies/{id}', [FinishingCompanyController::class, 'publicShow']);
    Route::get('/statuses', [\App\Http\Controllers\Api\StatusLocationController::class, 'getAllStatuses']);
    Route::get('/locations', [\App\Http\Controllers\Api\StatusLocationController::class, 'getAllLocations']);

    Route::get('/search', [PropertyController::class, 'search']);
});

// Customer property management
Route::middleware('auth:api')->prefix('customer')->group(function () {
    // My properties
    Route::get('/properties', [\App\Http\Controllers\Customer\CustomerPropertyController::class, 'index']);
    Route::post('/properties', [\App\Http\Controllers\Customer\CustomerPropertyController::class, 'store']);
    Route::put('/properties/{id}', [\App\Http\Controllers\Customer\CustomerPropertyController::class, 'update']);
    Route::patch('/properties/{id}', [\App\Http\Controllers\Customer\CustomerPropertyController::class, 'update']);
    Route::delete('/properties/{id}', [\App\Http\Controllers\Customer\CustomerPropertyController::class, 'destroy']);
    Route::patch('/properties/{id}/contact-visibility', [\App\Http\Controllers\Customer\CustomerPropertyController::class, 'updateContactVisibility']);

    // Requests received on my properties
    Route::get('/properties/{id}/requests', [\App\Http\Controllers\Customer\CustomerPropertyRequestController::class, 'received']);
    Route::post('/properties/{property_id}/requests/{request_id}/respond', [\App\Http\Controllers\Customer\CustomerPropertyRequestController::class, 'respond']);

    // My sent requests
    Route::get('/requests', [\App\Http\Controllers\Customer\CustomerPropertyRequestController::class, 'sent']);
    Route::put('/requests/{id}', [\App\Http\Controllers\Customer\CustomerPropertyRequestController::class, 'update']);
    Route::patch('/requests/{id}', [\App\Http\Controllers\Customer\CustomerPropertyRequestController::class, 'update']);
    Route::delete('/requests/{id}', [\App\Http\Controllers\Customer\CustomerPropertyRequestController::class, 'destroy']);
});

// Public endpoint for sending a request to a property
Route::post('/properties/{id}/requests', [\App\Http\Controllers\Customer\CustomerPropertyRequestController::class, 'store'])->middleware('auth:api');

// Customer property management
Route::middleware('auth:api')->prefix('customer')->group(function () {
    // My properties
    Route::get('/properties', [\App\Http\Controllers\Customer\CustomerPropertyController::class, 'index']);
    Route::post('/properties', [\App\Http\Controllers\Customer\CustomerPropertyController::class, 'store']);
    Route::put('/properties/{id}', [\App\Http\Controllers\Customer\CustomerPropertyController::class, 'update']);
    Route::patch('/properties/{id}', [\App\Http\Controllers\Customer\CustomerPropertyController::class, 'update']);
    Route::delete('/properties/{id}', [\App\Http\Controllers\Customer\CustomerPropertyController::class, 'destroy']);
    Route::patch('/properties/{id}/contact-visibility', [\App\Http\Controllers\Customer\CustomerPropertyController::class, 'updateContactVisibility']);

    // Requests received on my properties
    Route::get('/properties/{propertyId}/requests', [\App\Http\Controllers\Customer\CustomerPropertyRequestController::class, 'received']);
    Route::post('/properties/{propertyId}/requests/{requestId}/respond', [\App\Http\Controllers\Customer\CustomerPropertyRequestController::class, 'respond']);

    // My sent requests
    Route::get('/requests', [\App\Http\Controllers\Customer\CustomerPropertyRequestController::class, 'sent']);
    Route::put('/requests/{id}', [\App\Http\Controllers\Customer\CustomerPropertyRequestController::class, 'update']);
    Route::patch('/requests/{id}', [\App\Http\Controllers\Customer\CustomerPropertyRequestController::class, 'update']);
    Route::delete('/requests/{id}', [\App\Http\Controllers\Customer\CustomerPropertyRequestController::class, 'destroy']);
});

// Public endpoint for sending a request to a property
Route::post('/properties/{propertyId}/requests', [\App\Http\Controllers\Customer\CustomerPropertyRequestController::class, 'store'])->middleware('auth:api');

// Finishing companies (public)
Route::get('/finishing-companies', [\App\Http\Controllers\Customer\CustomerFinishingCompanyController::class, 'index']);
Route::get('/finishing-companies/{id}', [\App\Http\Controllers\Customer\CustomerFinishingCompanyController::class, 'show']);

// Customer finishing requests (authenticated)
Route::middleware('auth:api')->prefix('customer')->group(function () {
    Route::get('/finishing-requests', [\App\Http\Controllers\Customer\CustomerFinishingRequestController::class, 'index']);
    Route::get('/finishing-requests/{id}', [\App\Http\Controllers\Customer\CustomerFinishingRequestController::class, 'show']);
    Route::post('/finishing-requests', [\App\Http\Controllers\Customer\CustomerFinishingRequestController::class, 'store']);
});
