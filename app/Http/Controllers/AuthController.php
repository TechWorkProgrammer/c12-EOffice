<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Models\MUser;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
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
        } catch (\Exception $e) {
            return ResponseHelper::InternalServerError($e->getMessage());
        }
    }

    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email|exists:m_users',
                'password' => 'required|string',
            ]);
            $credentials = $request->only('email', 'password');
            if (!$token = Auth::guard('api')->attempt($credentials)) {
                return ResponseHelper::Unauthorized('Email tidak terdaftar atau Password Salah');
            }
            return ResponseHelper::Success('login successful', ['user' => Auth::guard('api')->user(), 'token' => $token]);
        } catch (\Exception $e) {
            return ResponseHelper::InternalServerError($e->getMessage());
        }
    }

    public function logout(): JsonResponse
    {
        Auth::guard('api')->logout();
        return ResponseHelper::Success('Successfully logged out');
    }
}
