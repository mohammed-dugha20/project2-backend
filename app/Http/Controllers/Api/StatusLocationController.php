<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Status;
use App\Models\Location;

class StatusLocationController extends Controller
{
    public function getAllStatuses()
    {
        return response()->json(Status::all());
    }

    public function getAllLocations()
    {
        return response()->json(Location::all());
    }
} 