<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
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

    public function loginUser(Request $request)
    {
        try {
            // Validasi input dari request
            $credentials = $request->validate([
                'phone_number' => 'required|string',
                'password' => 'required|string',
            ]);

            // Mengecek kredensial user
            if (!auth()->attempt($credentials)) {
                return ResponseHelper::Unauthorized('invalid phone number or password');
            }

            // Mendapatkan user yang berhasil login
            $user = auth()->user();

            // Membuat token untuk user
            $token = $user->createToken('authToken')->plainTextToken;

            $data = [
                'user' => [
                    'name' => $user->name,
                    'phone_number' => $user->phone_number,
                    'point' => $user->point,
                ],
                'token' => $token,
            ];

            return ResponseHelper::Success('login successful', $data);
        } catch (\Exception $e) {
            return ResponseHelper::InternalServerError($e->getMessage());
        }
    }

    public function sendOtp(Request $request)
    {
        try {
            // Validasi input phone_number
            $data = $request->validate([
                'phone_number' => 'required|string|max:15',
            ]);

            // Generate kode OTP unik
            // $otp = rand(100000, 999999);
            $otp = 123456;

            // Kirim OTP ke WhatsApp menggunakan API pihak ketiga
            $this->sendOtpToWhatsapp($data['phone_number'], $otp);

            // Simpan OTP dalam cache dengan masa kadaluarsa 5 menit
            Cache::put('otp_' . $data['phone_number'], $otp, now()->addMinutes(5));

            return ResponseHelper::Success('OTP sent successfully', $data['phone_number']);
        } catch (\Exception $e) {
            return ResponseHelper::InternalServerError($e->getMessage());
        }
    }

    private function sendOtpToWhatsapp($phoneNumber, $otp)
    {
        // Contoh logika pengiriman OTP ke WhatsApp menggunakan API pihak ketiga
        // $whatsappApiUrl = 'https://api.whatsapp.com/send';
        // $message = "Your OTP code is: $otp";

        // Gantikan ini dengan logika pengiriman sebenarnya menggunakan API yang diinginkan
        // Http::post($whatsappApiUrl, [
        //     'phone_number' => $phoneNumber,
        //     'message' => $message,
        // ]);
    }

    public function verifyOtp(Request $request)
    {
        // Validasi input
        $data = $request->validate([
            'phone_number' => 'required|string|max:15',
            'otp' => 'required|integer'
        ]);

        // Ambil OTP dari cache
        $cachedOtp = Cache::get('otp_' . $data['phone_number']);

        if (!$cachedOtp) {
            return ResponseHelper::BadRequest('OTP has expired or does not exist');
        }

        if ($cachedOtp == $data['otp']) {
            // Hapus OTP setelah verifikasi berhasil
            Cache::forget('otp_' . $data['phone_number']);
            return ResponseHelper::Success('OTP verified successfully');
        }

        return ResponseHelper::BadRequest('invalid OTP');
    }
}
