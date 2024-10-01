<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Models\MPejabat;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PejabatController extends Controller
{
    public function index(): JsonResponse
    {
        $datas = MPejabat::all();
        return ResponseHelper::Success('pejabat retrieved successfully', $datas);
    }

    public function store(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'atasan_id' => 'required|exists:m_pejabats,uuid',
            'name' => 'required|string|max:255'
        ]);

        MPejabat::create($validatedData);

        return ResponseHelper::Created('pejabat created successfully');
    }
}
