<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Models\PojokEdukasi;
use App\Models\Program;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        foreach ($users as $user) {
            $user->is_verified = $user->is_verified ? "Sudah" : "Belum";
            $user->questioner_submitted = $user->questioner_submitted ? "Sudah" : "Belum";
        }

        return view('data.pengguna', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $user = User::create($request->all());
        return redirect()->route('users.index');
    }

    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $user->update($request->all());
        return redirect()->route('users.index');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index');
    }

    public function home(Request $request)
    {
        try {
            // Mendapatkan user yang sedang login
            // $user = auth()->user();
            $validatedData = $request->validate([
                'user_id' => 'required|integer|exists:users,id',
            ]);

            $user = User::find($validatedData['user_id']);

            // Jika user tidak ditemukan, kembalikan response error
            if (!$user) {
                return ResponseHelper::BadRequest('user not found');
            }

            // Mengambil daftar program
            $programs = Program::all(['id', 'name', 'image']);

            // Mengambil daftar pojok edukasi
            $pojokEdukasi = PojokEdukasi::all(['id', 'name', 'image']);

            // Menyusun data untuk response
            $data = [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'point' => $user->point,
                ],
                'programs' => $programs,
                'pojok_edukasi' => $pojokEdukasi,
            ];

            // Mengembalikan response dalam bentuk JSON
            return ResponseHelper::Success('user home data retrieved successfully', $data);
        } catch (\Exception $e) {
            // Mengembalikan response error jika terjadi exception
            return ResponseHelper::InternalServerError('failed to retrieve user home data');
        }
    }
}
