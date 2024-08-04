<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Models\Program;
use Exception;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    public function index(Request $request)
    {
        try {
            // Mengambil semua program beserta konten-kontennya
            $programs = Program::with(['admin', 'contents'])->get();
            if ($request->wantsJson()) {
                // Jika request ingin JSON  
                return ResponseHelper::Success('programs retrieved successfully', $programs);
            } else {
                // Jika request ingin view
                return view('data.program', compact('programs'));
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
        return view('programs.create');
    }

    public function store(Request $request)
    {
        $program = Program::create($request->all());
        return redirect()->route('programs.index');
    }

    public function show(Program $program)
    {
        return view('programs.show', compact('program'));
    }

    public function edit(Program $program)
    {
        return view('programs.edit', compact('program'));
    }

    public function update(Request $request, Program $program)
    {
        $program->update($request->all());
        return redirect()->route('programs.index');
    }

    public function destroy(Program $program)
    {
        $program->delete();
        return redirect()->route('programs.index');
    }
}
