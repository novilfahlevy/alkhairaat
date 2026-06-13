<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreBulkFileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::user()->hasRole([
            User::ROLE_SUPERUSER,
            User::ROLE_PENGURUS_BESAR,
            User::ROLE_KOMISARIAT_WILAYAH,
            User::ROLE_KOMISARIAT_DAERAH,
            User::ROLE_SEKOLAH,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'file' => [
                'required',
                'file',
                // 'mimes:xlsx,xls,csv',
                'max:5120', // 5MB in kilobytes
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'file.required' => 'File harus diunggah.',
            'file.file' => 'Input harus berupa file.',
            'file.mimes' => 'Format file harus Excel (.xlsx, .xls) atau CSV (.csv).',
            'file.max' => 'Ukuran file tidak boleh melebihi 5MB.',
        ];
    }
}
