<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Models\PojokEdukasi;
use Exception;
use Illuminate\Http\Request;

class PojokEdukasiController extends Controller
{
    public function index(Request $request)
    {
        try {
            // Mengambil semua program beserta konten-kontennya
            $pojokEdukasis = PojokEdukasi::with(['admin', 'contents'])->get();
            if ($request->wantsJson()) {
                // Jika request ingin JSON
                return ResponseHelper::Success('pojok edukasi retrieved successfully', $pojokEdukasis);
            } else {
                // Jika request ingin view
                return view('data.pojok_edukasi', compact('pojokEdukasis'));
            }
        } catch (Exception $e) {
            // Menangani exception dan mengembalikan error message
            if ($request->wantsJson()) {
                return ResponseHelper::InternalServerError($e->getMessage());
            }
            // else {
            //     return response()->view('errors.500', ['error' => $e->getMessage()], 500);
            // }
        }
    }

    public function create()
    {
        return view('pojokEdukasis.create');
    }

    public function store(Request $request)
    {
        $pojokEdukasi = PojokEdukasi::create($request->all());
        return redirect()->route('pojokEdukasis.index');
    }

    public function show(PojokEdukasi $pojokEdukasi)
    {
        return view('pojokEdukasis.show', compact('pojokEdukasi'));
    }

    public function edit(PojokEdukasi $pojokEdukasi)
    {
        return view('pojokEdukasis.edit', compact('pojokEdukasi'));
    }

    public function update(Request $request, PojokEdukasi $pojokEdukasi)
    {
        $pojokEdukasi->update($request->all());
        return redirect()->route('pojokEdukasis.index');
    }

    public function destroy(PojokEdukasi $pojokEdukasi)
    {
        $pojokEdukasi->delete();
        return redirect()->route('pojokEdukasis.index');
    }
}
