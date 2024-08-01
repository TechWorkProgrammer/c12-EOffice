<?php

namespace App\Http\Controllers;

use App\Models\PojokEdukasiContent;
use Illuminate\Http\Request;

class PojokEdukasiContentController extends Controller
{
    public function index()
    {
        $contents = PojokEdukasiContent::all();
        return view('pojokEdukasiContents.index', compact('contents'));
    }

    public function create()
    {
        return view('pojokEdukasiContents.create');
    }

    public function store(Request $request)
    {
        $content = PojokEdukasiContent::create($request->all());
        return redirect()->route('pojokEdukasiContents.index');
    }

    public function show(PojokEdukasiContent $content)
    {
        return view('pojokEdukasiContents.show', compact('content'));
    }

    public function edit(PojokEdukasiContent $content)
    {
        return view('pojokEdukasiContents.edit', compact('content'));
    }

    public function update(Request $request, PojokEdukasiContent $content)
    {
        $content->update($request->all());
        return redirect()->route('pojokEdukasiContents.index');
    }

    public function destroy(PojokEdukasiContent $content)
    {
        $content->delete();
        return redirect()->route('pojokEdukasiContents.index');
    }
}
