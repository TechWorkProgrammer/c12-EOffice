<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'phone_number' => 'required|string|max:15|unique:users,phone_number',
            'password' => 'required|string|min:8|confirmed',
            'address' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'name' => 'required|string|max:255',
            'birthdate' => [
                'required',
                'date',
                function ($attribute, $value, $fail) {
                    if (Carbon::parse($value)->age < 13) {
                        $fail('Anda harus berusia setidaknya 13 tahun.');
                    }
                },
            ],
        ]);

        $user = new User();
        $user->phone_number = $validatedData['phone_number'];
        $user->password = Hash::make($validatedData['password']);
        $user->address = $validatedData['address'];
        $user->email = $validatedData['email'] ?? "";
        $user->name = $validatedData['name'];
        $user->birthdate = $validatedData['birthdate'];
        $user->role = User::ROLE_USER;
        $user->point = 0;

        $user->save();

        if (!$token = Auth::guard('api')->attempt(['phone_number' => $validatedData['phone_number'], 'password' => $validatedData['password']])) {
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
            'phone_number' => 'required|string|max:15|exists:users,phone_number',
            'password' => 'required|string',
        ]);
        $credentials = $request->only('phone_number', 'password');
        if (!$token = Auth::guard('api')->attempt($credentials)) {
            return ResponseHelper::Unauthorized('No tidak terdaftar atau Password Salah');
        }
        return ResponseHelper::Success('login successful', ['user' => Auth::guard('api')->user(), 'token' => $token]);
    }

    public function logout(): JsonResponse
    {
        Auth::guard('api')->logout();
        return ResponseHelper::Success('Successfully logged out');
    }
}
