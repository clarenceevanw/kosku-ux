<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validates incoming registration data.
 * Role is restricted to 'owner' or 'tenant' — guests cannot self-register as 'admin'.
 */
class RegisterUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'         => ['required', 'string', 'max:255'],
            'email'        => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone_number' => ['required', 'string', 'max:20'],
            'password'     => ['required', 'string', 'min:8', 'confirmed'],
            'role'         => ['required', 'in:owner,tenant'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'         => 'Nama lengkap wajib diisi.',
            'email.required'        => 'Alamat email wajib diisi.',
            'email.email'           => 'Format email tidak valid.',
            'email.unique'          => 'Email ini sudah terdaftar.',
            'phone_number.required' => 'Nomor telepon wajib diisi.',
            'password.required'     => 'Password wajib diisi.',
            'password.min'          => 'Password minimal 8 karakter.',
            'password.confirmed'    => 'Konfirmasi password tidak cocok.',
            'role.required'         => 'Pilih tipe akun terlebih dahulu.',
            'role.in'               => 'Tipe akun tidak valid.',
        ];
    }
}
