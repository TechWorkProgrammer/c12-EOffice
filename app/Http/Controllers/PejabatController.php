<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Models\MPejabat;
use Illuminate\Http\Request;

class PejabatController extends Controller
{
    public function index() {
        $datas = MPejabat::all();
        return ResponseHelper::Success('pejabat retrieved successfully', $datas);
    }
}
