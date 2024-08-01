<?php

namespace App\Http\Controllers;

use App\Models\HistoryPoint;
use Illuminate\Http\Request;

class HistoryPointController extends Controller
{
    public function index()
    {
        $historyPoints = HistoryPoint::all();
        return view('historyPoints.index', compact('historyPoints'));
    }

    public function create()
    {
        return view('historyPoints.create');
    }

    public function store(Request $request)
    {
        $historyPoint = HistoryPoint::create($request->all());
        return redirect()->route('historyPoints.index');
    }

    public function show(HistoryPoint $historyPoint)
    {
        return view('historyPoints.show', compact('historyPoint'));
    }

    public function edit(HistoryPoint $historyPoint)
    {
        return view('historyPoints.edit', compact('historyPoint'));
    }

    public function update(Request $request, HistoryPoint $historyPoint)
    {
        $historyPoint->update($request->all());
        return redirect()->route('historyPoints.index');
    }

    public function destroy(HistoryPoint $historyPoint)
    {
        $historyPoint->delete();
        return redirect()->route('historyPoints.index');
    }
}
