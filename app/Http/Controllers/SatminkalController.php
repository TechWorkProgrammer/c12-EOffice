<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Models\Satminkal;
use Illuminate\Http\Request;

class SatminkalController extends Controller
{
    public function index() {
        $datas = Satminkal::with('kotama')->get();
        return ResponseHelper::Success('data satminkal retrieved successfully', $datas);
    }

    public function store(Request $request) {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'kode_kotama' => 'required|string|max:255',
            'kode_satminkal' => 'required|string|max:255',
        ]);

        Satminkal::create($validatedData);

        return ResponseHelper::Created('Satminkal created successfully');
    }
}
