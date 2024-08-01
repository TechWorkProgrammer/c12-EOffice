<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use Illuminate\Http\Request;

class DeliveryController extends Controller
{
    public function index()
    {
        $deliveries = Delivery::all();
        return view('deliveries.index', compact('deliveries'));
    }

    public function create()
    {
        return view('deliveries.create');
    }

    public function store(Request $request)
    {
        $delivery = Delivery::create($request->all());
        return redirect()->route('deliveries.index');
    }

    public function show(Delivery $delivery)
    {
        return view('deliveries.show', compact('delivery'));
    }

    public function edit(Delivery $delivery)
    {
        return view('deliveries.edit', compact('delivery'));
    }

    public function update(Request $request, Delivery $delivery)
    {
        $delivery->update($request->all());
        return redirect()->route('deliveries.index');
    }

    public function destroy(Delivery $delivery)
    {
        $delivery->delete();
        return redirect()->route('deliveries.index');
    }
}
