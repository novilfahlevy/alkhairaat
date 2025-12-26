<?php

namespace App\Http\Requests;

use App\Models\JabatanGuru;
use Illuminate\Foundation\Http\FormRequest;

class StoreExistingGuruRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_guru' => ['required', 'integer', 'exists:guru,id'],
            'jenis_jabatan' => ['required', 'in:' . implode(',', array_keys(JabatanGuru::JENIS_JABATAN_OPTIONS))],
            'keterangan_jabatan' => ['required', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'id_guru.required' => 'Pilih guru terlebih dahulu.',
            'id_guru.integer' => 'ID guru tidak valid.',
            'id_guru.exists' => 'Guru yang dipilih tidak ditemukan.',
            'jenis_jabatan.required' => 'Jenis jabatan wajib dipilih.',
            'jenis_jabatan.in' => 'Jenis jabatan tidak valid.',
            'keterangan_jabatan.required' => 'Keterangan jabatan wajib diisi.',
            'keterangan_jabatan.string' => 'Keterangan jabatan harus berupa teks.',
            'keterangan_jabatan.max' => 'Keterangan jabatan maksimal 255 karakter.',
        ];
    }
}
