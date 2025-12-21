<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\User;

class UpdateKomdaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()?->isKomisariatWilayah();
    }

    public function rules(): array
    {
        $userId = $this->route('komda')->id ?? $this->route('komda');
        return [
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:100', Rule::unique('users', 'username')->ignore($userId)],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($userId)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => ['nullable'],
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
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak sesuai.',
            'sekolah_ids.*.exists' => 'Sekolah yang dipilih tidak ditemukan.',
        ];
    }
}
