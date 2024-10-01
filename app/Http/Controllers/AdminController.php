<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Models\MUser;
use Illuminate\Http\JsonResponse;

class AdminController extends Controller
{
    public function allUser(): JsonResponse
    {
        $datas = MUser::all();
        if ($datas->isEmpty()) {
            return ResponseHelper::NotFound('no data user found');
        }
        return ResponseHelper::Success('data all user retrieved successfully', $datas);
    }
}
