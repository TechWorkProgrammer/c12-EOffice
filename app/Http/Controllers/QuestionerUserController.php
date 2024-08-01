<?php

namespace App\Http\Controllers;

use App\Models\QuestionerUser;
use Illuminate\Http\Request;

class QuestionerUserController extends Controller
{
    public function index()
    {
        $questionerUsers = QuestionerUser::all();
        return view('questionerUsers.index', compact('questionerUsers'));
    }

    public function create()
    {
        return view('questionerUsers.create');
    }

    public function store(Request $request)
    {
        $questionerUser = QuestionerUser::create($request->all());
        return redirect()->route('questionerUsers.index');
    }

    public function show(QuestionerUser $questionerUser)
    {
        return view('questionerUsers.show', compact('questionerUser'));
    }

    public function edit(QuestionerUser $questionerUser)
    {
        return view('questionerUsers.edit', compact('questionerUser'));
    }

    public function update(Request $request, QuestionerUser $questionerUser)
    {
        $questionerUser->update($request->all());
        return redirect()->route('questionerUsers.index');
    }

    public function destroy(QuestionerUser $questionerUser)
    {
        $questionerUser->delete();
        return redirect()->route('questionerUsers.index');
    }
}

