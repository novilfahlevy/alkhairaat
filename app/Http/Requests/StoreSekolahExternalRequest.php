<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Models\Sekolah;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

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
            'jenis_sekolah' => ['required', Rule::in(array_keys(Sekolah::JENIS_SEKOLAH_OPTIONS))],
            'bentuk_pendidikan' => ['required', Rule::in(array_keys(Sekolah::BENTUK_PENDIDIKAN_OPTIONS))],
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
            'jenis_sekolah.required' => 'Jenis sekolah harus dipilih.',
            'jenis_sekolah.in' => 'Jenis sekolah yang dipilih tidak valid.',
            'bentuk_pendidikan.required' => 'Bentuk pendidikan harus dipilih.',
            'bentuk_pendidikan.in' => 'Bentuk pendidikan yang dipilih tidak valid.',
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
            'jenis_sekolah' => 'jenis sekolah',
            'bentuk_pendidikan' => 'bentuk pendidikan',
            'nama_sekolah' => 'nama sekolah',
            'kota_sekolah' => 'kota sekolah',
        ];
    }
}
