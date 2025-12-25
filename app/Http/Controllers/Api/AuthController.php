<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Register User Baru
     */
    public function register(RegisterRequest $request)
    {
        // 1. Validasi sudah ditangani otomatis oleh RegisterRequest.
        // Jika gagal, Laravel langsung melempar respons JSON Error 422.

        // 2. Buat User
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            // Hashing: Wajib menggunakan Hash::make
            'password' => Hash::make($request->password),
        ]);

        // 3. Buat Token Sanctum
        $token = $user->createToken('auth_token')->plainTextToken;

        // 4. Return JSON Response (Standar API)
        return response()->json([
            'data' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 201);
    }

    /**
     * Login User
     */
    public function login(LoginRequest $request)
    {
        // Cari user by email
        $user = User::where('email', $request->email)->first();

        // Security Check:
        // Cek apakah user ada DAN password cocok.
        // Jangan beritahu spesifik "Email salah" atau "Password salah"
        // Gunakan pesan umum "Invalid credentials" untuk mencegah User Enumeration Attack.
        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Kredensial yang diberikan salah.'],
            ]);
        }

        // Jika berhasil, hapus token lama (opsional, untuk single device login)
        // $user->tokens()->delete(); 

        // Buat token baru
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login berhasil',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ]);
    }

    /**
     * Logout User
     */
    public function logout(Request $request)
    {
        // Hapus token yang sedang digunakan saat ini saja
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Token berhasil dihapus (Logout sukses)'
        ]);
    }
}