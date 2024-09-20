<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Models\MUser;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function allUser() {
        try {
            //code...
            $datas = MUser::all();
            return ResponseHelper::Success('data all user retrieved successfully', $datas);
        } catch (\Exception $e) {
            return ResponseHelper::Success('data all user retrieved successfully', $e->getMessage());
            //throw $th;

        }
    }
}
