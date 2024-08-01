<?php

namespace App\Http\Controllers;

use App\Models\PojokEdukasi;
use Illuminate\Http\Request;

class PojokEdukasiController extends Controller
{
    public function index()
    {
        $pojokEdukasis = PojokEdukasi::all();
        return view('data.pojok_edukasi', compact('pojokEdukasis'));
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
