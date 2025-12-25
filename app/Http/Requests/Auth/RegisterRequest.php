<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Ubah jadi true agar request diizinkan
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            // Password::defaults() menggunakan standar keamanan Laravel (minimal 8 char, dll)
            'password' => ['required', 'confirmed', Password::defaults()],
        ];
    }
}