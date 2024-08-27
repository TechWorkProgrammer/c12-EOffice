<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Models\Program;
use App\Models\ProgramContent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProgramContentController extends Controller
{
    public function index(Program $program): JsonResponse
    {
        $contents = $program->contents;
        return ResponseHelper::Success('Program contents retrieved successfully', $contents);
    }

    public function store(Request $request, Program $program): JsonResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'link' => 'nullable|url',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp,heic|max:2048',
        ]);

        if ($request->hasFile('cover')) {
            $path = $request->file('cover')->store('public/images');
            $validatedData['cover'] = env('APP_URL') . Storage::url($path);
        }
        $validatedData['program_id'] = $program->uuid;
        $content = ProgramContent::create($validatedData);
        return ResponseHelper::Created('Program content created successfully', $content);
    }

    public function show(ProgramContent $programContent): JsonResponse
    {
        return ResponseHelper::Success('Program content retrieved successfully', $programContent);
    }

    public function update(Request $request, ProgramContent $programContent): JsonResponse
    {
        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'link' => 'nullable|url',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp,heic|max:2048',
        ]);

        if ($request->hasFile('cover')) {
            if ($programContent->cover) {
                Storage::delete(str_replace(env('APP_URL') . '/storage/', 'public/', $programContent->cover));
            }
            $path = $request->file('cover')->store('public/images');
            $validatedData['cover'] = env('APP_URL') . Storage::url($path);
        }
        $programContent->update($validatedData);
        return ResponseHelper::Success('Program content updated successfully', $programContent);
    }

    public function destroy(ProgramContent $programContent): JsonResponse
    {
        if ($programContent->cover) {
            Storage::delete(str_replace(env('APP_URL') . '/storage/', 'public/', $programContent->cover));
        }
        $programContent->delete();
        return ResponseHelper::Success('Program content deleted successfully');
    }
}
