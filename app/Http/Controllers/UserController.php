<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Models\User;
use App\Models\UserToken;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(): JsonResponse
    {
        $users = User::all();
        return ResponseHelper::Success('User data retrieved successfully', $users);
    }

    public function showDrivers(): JsonResponse
    {
        $drivers = User::where('role', User::ROLE_DRIVER)->get();

        if ($drivers->isEmpty()) {
            return ResponseHelper::NotFound('No drivers found.');
        }

        return ResponseHelper::Success('Drivers retrieved successfully', $drivers);
    }

    public function show(): JsonResponse
    {
        $user = Auth::guard('api')->user();
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

    public function generateToken(Request $request, User $user): JsonResponse
    {
        $validatedData = $request->validate([
            'expires_in_days' => 'required|integer|min:1',
            'type' => 'required|string|in:reset_password,update_phone_number',
        ]);

        try {
            $token = bin2hex(random_bytes(16));
        } catch (Exception $e) {
            return ResponseHelper::InternalServerError('Failed to generate token: ' . $e->getMessage());
        }

        $expiresAt = now()->addDays($validatedData['expires_in_days']);

        UserToken::create([
            'user_id' => $user->uuid,
            'token' => $token,
            'type' => $validatedData['type'],
            'expires_at' => $expiresAt,
        ]);

        return ResponseHelper::Success('Token generated successfully', [
            'token' => $token,
            'expires_at' => $expiresAt,
            'type' => $validatedData['type'],
        ]);
    }

    public function updateProfile(Request $request): JsonResponse
    {
        $user = Auth::guard('api')->user();

        if (!$user instanceof User) {
            return ResponseHelper::Unauthorized('Invalid user instance.');
        }

        $validatedData = $request->validate([
            'current_password' => 'sometimes|required|string',
            'new_phone_number' => 'sometimes|required|string|unique:users,phone_number|max:15',
            'new_password' => 'sometimes|required|string|min:8|confirmed',
            'token' => 'sometimes|required|string|exists:user_tokens,token',
            'name' => 'sometimes|required|string|max:255',
            'address' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|max:255',
            'birthdate' => 'sometimes|required|date|before:today',
        ]);

        $requiresToken = !empty($validatedData['new_phone_number']) || (!empty($validatedData['new_password']) && empty($validatedData['current_password']));

        if ($requiresToken) {
            $userToken = UserToken::where('token', $validatedData['token'] ?? null)
                ->where('user_id', $user->uuid)
                ->where('expires_at', '>', now())
                ->first();

            if (!$userToken) {
                return ResponseHelper::Unauthorized('Invalid or expired token.');
            }
        }

        if (!empty($validatedData['new_password']) && empty($validatedData['token'])) {
            if (!Hash::check($validatedData['current_password'], $user->password)) {
                return ResponseHelper::Unauthorized('Current password is incorrect.');
            }
        }

        if (!empty($validatedData['new_phone_number'])) {
            $user->phone_number = $validatedData['new_phone_number'];
        }

        if (!empty($validatedData['new_password'])) {
            $user->password = Hash::make($validatedData['new_password']);
        }

        if (!empty($request->input('name'))) {
            $user->name = $request->input('name');
        }

        if (!empty($request->input('address'))) {
            $user->address = $request->input('address');
        }

        if (!empty($request->input('email'))) {
            $user->email = $request->input('email');
        }

        if (!empty($validatedData['birthdate'])) {
            $user->birthdate = $validatedData['birthdate'];
        }

        $user->save();

        if ($requiresToken && isset($userToken)) {
            $userToken->delete();
        }

        return ResponseHelper::Success('Profile updated successfully', $user);
    }
}
