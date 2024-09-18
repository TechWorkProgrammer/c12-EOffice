<?php

namespace App\Http\Controllers;

use App\Helpers\AuthHelper;
use App\Helpers\ResponseHelper;
use App\Models\MUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'name' => 'required|string|max:255',
            'pejabat_id' => 'nullable|string|max:255',
            'satminkal_id' => 'required|string|max:255',
        ]);

        $user = MUser::create($validatedData);

        $credentials = $request->only('email', 'password');
        if (!$token = Auth::guard('api')->attempt($credentials)) {
            return ResponseHelper::Unauthorized('Gagal melakukan autentikasi setelah registrasi.');
        }

        return ResponseHelper::Success('Akun berhasil dibuat dan login', [
            'user' => $user,
            'token' => $token,
        ]);

    }

    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email|exists:m_users',
            'password' => 'required|string',
        ]);
        $credentials = $request->only('email', 'password');
        if (!$token = Auth::guard('api')->attempt($credentials)) {
            return ResponseHelper::Unauthorized('Email tidak terdaftar atau Password Salah');
        }

        $userInfo = MUser::with('pejabat')->find(AuthHelper::getAuthenticatedUser()->uuid);
        return ResponseHelper::Success('login successful', ['user' => $userInfo, 'token' => $token]);
    }

    public function logout(): JsonResponse
    {
        Auth::guard('api')->logout();
        return ResponseHelper::Success('Successfully logged out');
    }
}
