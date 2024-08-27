<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Models\Education;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EducationController extends Controller
{
    public function index(): JsonResponse
    {
        $educations = Education::all();
        return ResponseHelper::Success('Educations retrieved successfully', $educations);
    }

    public function store(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp,heic|max:2048',
            'link' => 'nullable|url'
        ]);

        if ($request->hasFile('cover')) {
            $path = $request->file('cover')->store('public/images');
            $validatedData['cover'] = env('APP_URL') . Storage::url($path);
        }

        $education = Education::create($validatedData);

        return ResponseHelper::Created('Education created successfully', $education);
    }

    public function show(Education $education): JsonResponse
    {
        return ResponseHelper::Success('Education retrieved successfully', $education);
    }

    public function update(Request $request, Education $education): JsonResponse
    {
        $validatedData = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp,heic|max:2048',
            'link' => 'nullable|url'
        ]);

        if ($request->hasFile('cover')) {
            if ($education->cover) {
                Storage::delete(str_replace(env('APP_URL') . '/storage/', 'public/', $education->cover));
            }

            $path = $request->file('cover')->store('public/images');
            $validatedData['cover'] = env('APP_URL') . Storage::url($path);
        }

        $education->update($validatedData);

        return ResponseHelper::Success('Education updated successfully', $education);
    }

    public function destroy(Education $education): JsonResponse
    {
        if ($education->cover) {
            Storage::delete(str_replace(env('APP_URL') . '/storage/', 'public/', $education->cover));
        }

        $education->delete();
        return ResponseHelper::Success('Education deleted successfully');
    }
}
