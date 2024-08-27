<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Models\Program;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProgramController extends Controller
{
    public function index(): JsonResponse
    {
        $programs = Program::with('contents')->get();
        return ResponseHelper::Success('Programs retrieved successfully', $programs);
    }

    public function store(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp,heic|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('public/images');
            $validatedData['image'] = env('APP_URL') . Storage::url($path);
        }

        $validatedData['created_by'] = Auth::guard('api')->user()['uuid'];

        $program = Program::create($validatedData);

        return ResponseHelper::Created('Program created successfully', $program);
    }

    public function show(Program $program): JsonResponse
    {
        $program->load(['contents', 'creator']);
        return ResponseHelper::Success('Program retrieved successfully', $program);
    }

    public function update(Request $request, Program $program): JsonResponse
    {
        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp,heic|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($program->image) {
                Storage::delete(str_replace(env('APP_URL') . '/storage/', 'public/', $program->image));
            }

            $path = $request->file('image')->store('public/images');
            $validatedData['image'] = env('APP_URL') . Storage::url($path);
        }

        $program->update($validatedData);

        return ResponseHelper::Success('Program updated successfully', $program);
    }

    public function destroy(Program $program): JsonResponse
    {
        if ($program->image) {
            Storage::delete(str_replace(env('APP_URL') . '/storage/', 'public/', $program->image));
        }

        $program->delete();
        return ResponseHelper::Success('Program deleted successfully');
    }
}
