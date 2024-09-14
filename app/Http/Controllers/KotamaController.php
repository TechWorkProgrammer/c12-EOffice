<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Models\Kotama;
use Illuminate\Http\Request;

class KotamaController extends Controller
{
    public function index() {
        $datas = Kotama::all();
        return ResponseHelper::Success('data satminkal retrieved successfully', $datas);
    }

    public function store(Request $request) {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'kode' => 'required|string|max:255',
        ]);

        Kotama::create($validatedData);

        return ResponseHelper::Created('kotama created successfully');
    }
}
