<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\User;

class StoreAkunSekolahRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()?->isKomisariatDaerah();
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:100', 'unique:users,username'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => ['required'],
            'sekolah_ids' => ['sometimes', 'array'],
            'sekolah_ids.*' => ['exists:sekolah,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama user harus diisi.',
            'name.max' => 'Nama user maksimal 255 karakter.',
            'username.required' => 'Username harus diisi.',
            'username.unique' => 'Username sudah digunakan.',
            'username.max' => 'Username maksimal 100 karakter.',
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Password harus diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak sesuai.',
            'sekolah_ids.*.exists' => 'Sekolah yang dipilih tidak ditemukan.',
        ];
    }
}
