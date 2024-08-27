<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(): JsonResponse
    {
        $users = User::all();
        return ResponseHelper::Success('User data retrieved successfully', $users);
    }

    public function show(User $user): JsonResponse
    {
        return ResponseHelper::Success('User retrieved successfully', $user);
    }

    public function detailForm(User $user): JsonResponse
    {
        $questioner = $user->questionerUsers()->with('questioner')->get()->map(function ($questionerUser) {
            if ($questionerUser->questioner) {
                return [
                    'question' => $questionerUser->questioner->question,
                    'answer' => $questionerUser->answer,
                ];
            }

            return [
                'question' => 'Pertanyaan tidak ditemukan',
                'answer' => $questionerUser->answer,
            ];
        });

        return ResponseHelper::Success('Detail form user retrieved successfully', $questioner);
    }

    public function update(Request $request, User $user): JsonResponse
    {
        $validatedData = $request->validate([
            'role' => 'required|string|in:user,driver,admin',
        ]);

        $user->update(['role' => $validatedData['role']]);

        return ResponseHelper::Success('Role Pengguna Berhasil Diganti');
    }
}
