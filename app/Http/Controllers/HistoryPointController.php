<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Models\HistoryPoint;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class HistoryPointController extends Controller
{
    public function index(): JsonResponse
    {
        $user = Auth::guard('api')->user();

        $historyPoints = HistoryPoint::where('user_id', $user->uuid)
            ->with(['delivery', 'prize'])
            ->get();

        return ResponseHelper::Success('History points retrieved successfully', $historyPoints);
    }
}
