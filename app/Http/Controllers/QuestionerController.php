<?php

namespace App\Http\Controllers;

use App\Models\Questioner;
use Illuminate\Http\Request;

class QuestionerController extends Controller
{
    public function index()
    {
        $questioners = Questioner::all();
        return view('data.formulir', compact('questioners'));
    }

    public function create()
    {
        return view('questioners.create');
    }

    public function store(Request $request)
    {
        $questioner = Questioner::create($request->all());
        return redirect()->route('questioners.index');
    }

    public function show(Questioner $questioner)
    {
        return view('questioners.show', compact('questioner'));
    }

    public function edit(Questioner $questioner)
    {
        return view('questioners.edit', compact('questioner'));
    }

    public function update(Request $request, Questioner $questioner)
    {
        $questioner->update($request->all());
        return redirect()->route('questioners.index');
    }

    public function destroy(Questioner $questioner)
    {
        $questioner->delete();
        return redirect()->route('questioners.index');
    }
}

