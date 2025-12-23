<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreSekolahExternalRequest extends FormRequest
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
            'id_jenis_sekolah' => ['required', 'exists:jenis_sekolah,id'],
            'id_bentuk_pendidikan' => ['required', 'exists:bentuk_pendidikan,id'],
            'nama_sekolah' => ['required', 'string', 'max:255'],
            'kota_sekolah' => ['required', 'string', 'max:100'],
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
            'id_jenis_sekolah.required' => 'Jenis sekolah harus dipilih.',
            'id_jenis_sekolah.exists' => 'Jenis sekolah yang dipilih tidak valid.',
            'id_bentuk_pendidikan.required' => 'Bentuk pendidikan harus dipilih.',
            'id_bentuk_pendidikan.exists' => 'Bentuk pendidikan yang dipilih tidak valid.',
            'nama_sekolah.required' => 'Nama sekolah harus diisi.',
            'nama_sekolah.max' => 'Nama sekolah tidak boleh lebih dari 255 karakter.',
            'kota_sekolah.required' => 'Kota sekolah harus diisi.',
            'kota_sekolah.max' => 'Kota sekolah tidak boleh lebih dari 100 karakter.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'id_jenis_sekolah' => 'jenis sekolah',
            'id_bentuk_pendidikan' => 'bentuk pendidikan',
            'nama_sekolah' => 'nama sekolah',
            'kota_sekolah' => 'kota sekolah',
        ];
    }
}
