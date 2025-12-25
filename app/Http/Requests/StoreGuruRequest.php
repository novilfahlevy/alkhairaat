<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGuruRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama' => ['required', 'string', 'max:255'],
            'nama_gelar_depan' => ['nullable', 'string', 'max:50'],
            'nama_gelar_belakang' => ['nullable', 'string', 'max:50'],
            'tempat_lahir' => ['nullable', 'string', 'max:100'],
            'tanggal_lahir' => ['nullable', 'date'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'status_perkawinan' => ['nullable', 'in:lajang,menikah'],
            'nik' => ['nullable', 'string', 'max:30'],
            'status_kepegawaian' => ['nullable', 'in:PNS,Non PNS,PPPK'],
            'npk' => ['nullable', 'string', 'max:30'],
            'nuptk' => ['nullable', 'string', 'max:30'],
            'kontak_wa_hp' => ['nullable', 'string', 'max:30'],
            'kontak_email' => ['nullable', 'email', 'max:100'],
            'nomor_rekening' => ['nullable', 'string', 'max:50'],
            'rekening_atas_nama' => ['nullable', 'string', 'max:100'],
            'bank_rekening' => ['nullable', 'string', 'max:50'],
            // alamat fields
            'alamat_provinsi' => ['nullable', 'string', 'max:100'],
            'alamat_kabupaten' => ['nullable', 'string', 'max:100'],
            'alamat_kecamatan' => ['nullable', 'string', 'max:100'],
            'alamat_kelurahan' => ['nullable', 'string', 'max:100'],
            'alamat_rt' => ['nullable', 'string', 'max:10'],
            'alamat_rw' => ['nullable', 'string', 'max:10'],
            'alamat_kode_pos' => ['nullable', 'string', 'max:10'],
            'alamat_lengkap' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function attributes(): array
    {
        return [
            'nama' => 'Nama Lengkap',
            'jenis_kelamin' => 'Jenis Kelamin',
        ];
    }
}
