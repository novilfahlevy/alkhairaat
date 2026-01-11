<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'username' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users', 'username')->ignore(auth()->id()),
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore(auth()->id()),
            ],
            'password' => [
                'nullable',
                'confirmed',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ],
        ];
    }

    /**
     * Get custom error messages.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama tidak boleh kosong.',
            'name.string' => 'Nama harus berupa teks.',
            'name.max' => 'Nama tidak boleh lebih dari 255 karakter.',
            'username.required' => 'Username tidak boleh kosong.',
            'username.unique' => 'Username sudah terdaftar.',
            'username.max' => 'Username tidak boleh lebih dari 255 karakter.',
            'email.required' => 'Email tidak boleh kosong.',
            'email.email' => 'Email harus format yang valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.mixed_case' => 'Password harus mengandung huruf besar dan kecil.',
            'password.numbers' => 'Password harus mengandung angka.',
            'password.symbols' => 'Password harus mengandung simbol (@$!%*?&).',
            'password.confirmed' => 'Konfirmasi password tidak sesuai.',
        ];
    }

    /**
     * Get the validated input as a collection, filtering out password if empty.
     */
    public function validated($key = null, $default = null): array
    {
        $validated = parent::validated();

        // Remove password if it's empty or not provided
        if (empty($validated['password'] ?? null)) {
            unset($validated['password']);
        }

        return $validated;
    }
}
