<?php

namespace App\Http\Controllers;

use App\Models\TrashPickup;
use Illuminate\Http\Request;

class TrashPickupController extends Controller
{
    public function index()
    {
        $trashPickups = TrashPickup::all();
        return view('trashPickups.index', compact('trashPickups'));
    }

    public function create()
    {
        return view('trashPickups.create');
    }

    public function store(Request $request)
    {
        $trashPickup = TrashPickup::create($request->all());
        return redirect()->route('trashPickups.index');
    }

    public function show(TrashPickup $trashPickup)
    {
        return view('trashPickups.show', compact('trashPickup'));
    }

    public function edit(TrashPickup $trashPickup)
    {
        return view('trashPickups.edit', compact('trashPickup'));
    }

    public function update(Request $request, TrashPickup $trashPickup)
    {
        $trashPickup->update($request->all());
        return redirect()->route('trashPickups.index');
    }

    public function destroy(TrashPickup $trashPickup)
    {
        $trashPickup->delete();
        return redirect()->route('trashPickups.index');
    }
}
