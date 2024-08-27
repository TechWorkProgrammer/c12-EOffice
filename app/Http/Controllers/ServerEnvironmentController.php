<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Models\Server;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ServerEnvironmentController extends Controller
{
    public function index(): JsonResponse
    {
        $environment = Server::first();

        if (!$environment) {
            return ResponseHelper::NotFound('Server environment not found.');
        }

        return ResponseHelper::Success('Server environment retrieved successfully.', $environment);
    }

    public function update(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'longitude' => 'required|numeric|between:-180,180',
            'latitude' => 'required|numeric|between:-90,90',
        ]);

        $environment = Server::first();

        if (!$environment) {
            $environment = Server::create($validatedData);
        } else {
            $environment->update($validatedData);
        }

        $environment->update($validatedData);

        return ResponseHelper::Success('Server environment updated successfully.', $environment);
    }
}
