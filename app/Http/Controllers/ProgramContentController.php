<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Models\Program;
use App\Models\ProgramContent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
        ]);

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
        ]);
        $programContent->update($validatedData);
        return ResponseHelper::Success('Program content updated successfully', $programContent);
    }

    public function destroy(ProgramContent $programContent): JsonResponse
    {
        $programContent->delete();
        return ResponseHelper::Success('Program content deleted successfully');
    }
}
