<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthenticationController extends Controller
{
    public function registerUser(Request $request)
    {
        try {
            // Validasi request menggunakan $request->validate
            $validatedData = $request->validate([
                'phone_number' => 'required|string|max:15|unique:users,phone_number',
                'password' => 'required|string|min:8|confirmed',
                'address' => 'required|string|max:255',
                'email' => 'nullable|email|max:255',
                'name' => 'required|string|max:255',
                'birthdate' => 'required|date',
            ]);

            // Buat instance User baru
            $user = new User();
            $user->phone_number = $validatedData['phone_number'];
            $user->password = Hash::make($validatedData['password']);
            $user->address = $validatedData['address'];
            $user->email = $validatedData['email'] ?? null;
            $user->name = $validatedData['name'];
            $user->birthdate = $validatedData['birthdate'];
            $user->role = 'user'; // default role
            $user->is_verified = false;
            $user->point = 0;
            $user->questioner_submitted = false;

            // Simpan user baru ke database
            $user->save();

            // Return response JSON dengan pesan sukses
            return ResponseHelper::Created('Success Create new Account', ['user' => $user]);

        } catch (Exception $e) {
            if ($e instanceof ValidationException) {
                return ResponseHelper::UnprocessableEntity($e->getMessage());
            }
            return ResponseHelper::InternalServerError($e->getMessage());
        }
    }

    public function loginUser()
    {
        return ResponseHelper::Created('Success Create new Account', null);
    }
}
