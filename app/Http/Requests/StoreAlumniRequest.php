<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAlumniRequest extends FormRequest
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
            'id_murid' => 'required|integer|exists:murid,id|unique:alumni,id_murid',
            'tahun_lulus' => 'required|integer|min:1900|max:' . (date('Y') + 5),
            'angkatan' => 'nullable|string|max:100',
            'kontak' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'alamat_sekarang' => 'nullable|string|max:255',
            'lanjutan_studi' => 'nullable|string|max:100',
            'nama_institusi' => 'nullable|string|max:150',
            'jurusan' => 'nullable|string|max:100',
            'pekerjaan' => 'nullable|string|max:100',
            'nama_perusahaan' => 'nullable|string|max:150',
            'jabatan' => 'nullable|string|max:100',
            'kota_perusahaan' => 'nullable|string|max:100',
            'riwayat_pekerjaan' => 'nullable|string',
            'keterangan' => 'nullable|string',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'id_murid' => 'Murid',
            'tahun_lulus' => 'Tahun Lulus',
            'angkatan' => 'Angkatan',
            'kontak' => 'Kontak (No HP)',
            'email' => 'Email',
            'alamat_sekarang' => 'Alamat Sekarang',
            'lanjutan_studi' => 'Lanjutan Studi',
            'nama_institusi' => 'Nama Institusi',
            'jurusan' => 'Jurusan',
            'pekerjaan' => 'Pekerjaan',
            'nama_perusahaan' => 'Nama Perusahaan',
            'jabatan' => 'Jabatan',
            'kota_perusahaan' => 'Kota Perusahaan',
            'riwayat_pekerjaan' => 'Riwayat Pekerjaan',
            'keterangan' => 'Keterangan',
        ];
    }

    /**
     * Get custom error messages.
     */
    public function messages(): array
    {
        return [
            'id_murid.required' => 'Murid harus dipilih',
            'id_murid.exists' => 'Murid tidak ditemukan',
            'id_murid.unique' => 'Murid sudah terdaftar sebagai alumni',
            'tahun_lulus.required' => 'Tahun lulus harus diisi',
            'tahun_lulus.integer' => 'Tahun lulus harus berupa angka',
            'tahun_lulus.min' => 'Tahun lulus tidak valid',
            'email.email' => 'Format email tidak valid',
        ];
    }
}
