<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Models\Education;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
            'link' => 'nullable|url'
        ]);

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
            'link' => 'nullable|url'
        ]);

        $education->update($validatedData);

        return ResponseHelper::Success('Education updated successfully', $education);
    }

    public function destroy(Education $education): JsonResponse
    {
        $education->delete();
        return ResponseHelper::Success('Education deleted successfully');
    }
}
