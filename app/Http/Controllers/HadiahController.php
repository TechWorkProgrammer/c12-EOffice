<?php

namespace App\Http\Controllers;

use App\Models\Hadiah;
use Illuminate\Http\Request;

class HadiahController extends Controller
{
    public function index()
    {
        $hadiahs = Hadiah::all();
        return view('data.hadiah', compact('hadiahs'));
    }

    public function create()
    {
        return view('hadiahs.create');
    }

    public function store(Request $request)
    {
        $hadiah = Hadiah::create($request->all());
        return redirect()->route('hadiahs.index');
    }

    public function show(Hadiah $hadiah)
    {
        return view('hadiahs.show', compact('hadiah'));
    }

    public function edit(Hadiah $hadiah)
    {
        return view('hadiahs.edit', compact('hadiah'));
    }

    public function update(Request $request, Hadiah $hadiah)
    {
        $hadiah->update($request->all());
        return redirect()->route('hadiahs.index');
    }

    public function destroy(Hadiah $hadiah)
    {
        $hadiah->delete();
        return redirect()->route('hadiahs.index');
    }
}
