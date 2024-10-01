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
            'role' => 'required|string|in:Tata Usaha,Pejabat,Pelaksana,External,Administrator',
            'email' => 'required|email|max:255',
            'name' => 'required|string|max:255',
            'pejabat_id' => 'nullable|string|max:255',
            'satminkal_id' => 'nullable|string|max:255',
        ]);

        $user = MUser::create($validatedData);

        return ResponseHelper::Success('Akun berhasil dibuat dan login', $user);

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
