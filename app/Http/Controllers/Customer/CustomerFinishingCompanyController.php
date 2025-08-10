<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CustomerFinishingCompanyController extends Controller
{
    /**
     * Display a listing of finishing companies with filters.
     */
    public function index(Request $request)
    {
        $query = \App\Models\FinishingCompany::query()->where('is_active', true);

        // Filter by city/area
        if ($request->filled('city')) {
            $query->whereHas('workareas.location', function ($q) use ($request) {
                $q->where('city', $request->city);
            });
        }
        if ($request->filled('area')) {
            $query->whereHas('workareas.location', function ($q) use ($request) {
                $q->where('neighborhood', $request->area);
            });
        }

        // Filter by finishing_type (service)
        if ($request->filled('finishing_type')) {
            $query->whereHas('services', function ($q) use ($request) {
                $q->where('service_type', $request->finishing_type);
            });
        }

        // Filter by rating
        if ($request->filled('rating')) {
            $query->whereHas('reviews', function ($q) use ($request) {
                $q->where('rating', '>=', $request->rating);
            });
        }

        // Filter by price_level (assume field or service)
        if ($request->filled('price_level')) {
            $query->where('price_level', $request->price_level);
        }

        $companies = $query->with(['services', 'workareas.location', 'portfolios', 'reviews'])
            ->paginate(10);

        return response()->json($companies);
    }

    /**
     * Display the profile of a specific finishing company.
     */
    public function show($id)
    {
        $company = \App\Models\FinishingCompany::with([
            'services',
            'workareas.location',
            'portfolios',
            'portfolios.images',
            'reviews.reviewer'
        ])->findOrFail($id);

        return response()->json([
            'id' => $company->id,
            'name' => $company->commercial_name ?? $company->name,
            'contact_info' => $company->contact_info,
            'profile_description' => $company->profile_description,
            'is_active' => $company->is_active,
            'services' => $company->services,
            'workareas' => $company->workareas,
            'portfolios' => $company->portfolios,
            'reviews' => $company->reviews,
            'created_at' => $company->created_at,
        ]);
    }
}
