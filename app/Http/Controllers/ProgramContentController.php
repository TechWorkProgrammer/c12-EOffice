<?php

namespace App\Http\Controllers;

use App\Models\ProgramContent;
use Illuminate\Http\Request;

class ProgramContentController extends Controller
{
    public function index()
    {
        $contents = ProgramContent::all();
        return view('programContents.index', compact('contents'));
    }

    public function create()
    {
        return view('programContents.create');
    }

    public function store(Request $request)
    {
        $content = ProgramContent::create($request->all());
        return redirect()->route('programContents.index');
    }

    public function show(ProgramContent $content)
    {
        return view('programContents.show', compact('content'));
    }

    public function edit(ProgramContent $content)
    {
        return view('programContents.edit', compact('content'));
    }

    public function update(Request $request, ProgramContent $content)
    {
        $content->update($request->all());
        return redirect()->route('programContents.index');
    }

    public function destroy(ProgramContent $content)
    {
        $content->delete();
        return redirect()->route('programContents.index');
    }
}
