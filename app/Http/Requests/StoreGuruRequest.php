<?php

namespace App\Http\Requests;

use App\Models\Guru;
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
            // wajib fields
            'nama' => ['required', 'string', 'max:255'],
            'nik' => ['required', 'string', 'max:30'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'jenis_jabatan' => ['required', 'in:' . implode(',', array_keys(\App\Models\JabatanGuru::JENIS_JABATAN_OPTIONS))],
            'keterangan_jabatan' => ['required', 'string', 'max:255'],
            'status' => ['required', 'in:' . implode(',', [Guru::STATUS_AKTIF, Guru::STATUS_TIDAK])],
            'status_kepegawaian' => ['required', 'in:' . implode(',', [Guru::STATUS_KEPEGAWAIAN_PNS, Guru::STATUS_KEPEGAWAIAN_NON_PNS, Guru::STATUS_KEPEGAWAIAN_PPPK])],

            // identitas fields
            'nama_gelar_depan' => ['nullable', 'string', 'max:50'],
            'nama_gelar_belakang' => ['nullable', 'string', 'max:50'],
            'tempat_lahir' => ['nullable', 'string', 'max:100'],
            'tanggal_lahir' => ['nullable', 'date'],
            'status_perkawinan' => ['nullable', 'in:' . implode(',', [Guru::STATUS_PERKAWINAN_LAJANG, Guru::STATUS_PERKAWINAN_MENIKAH])],
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

    public function messages(): array
    {
        return [
            // Wajib
            'nama.required' => 'Nama guru wajib diisi.',
            'nama.string' => 'Nama guru harus berupa teks.',
            'nama.max' => 'Nama guru maksimal 255 karakter.',
            'nik.required' => 'NIK wajib diisi.',
            'nik.string' => 'NIK harus berupa teks.',
            'nik.max' => 'NIK maksimal 30 karakter.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'jenis_kelamin.in' => 'Jenis kelamin harus Laki-laki (L) atau Perempuan (P).',
            'jenis_jabatan.required' => 'Jenis jabatan wajib dipilih.',
            'jenis_jabatan.in' => 'Jenis jabatan tidak valid.',
            'keterangan_jabatan.required' => 'Keterangan jabatan wajib diisi.',
            'keterangan_jabatan.string' => 'Keterangan jabatan harus berupa teks.',
            'keterangan_jabatan.max' => 'Keterangan jabatan maksimal 255 karakter.',
            'status.required' => 'Status keaktifan wajib dipilih.',
            'status.in' => 'Status keaktifan tidak valid.',
            'status_kepegawaian.required' => 'Status kepegawaian wajib dipilih.',
            'status_kepegawaian.in' => 'Status kepegawaian harus PNS, Non PNS, atau PPPK.',
            
            // Identitas
            'npk.string' => 'NPK harus berupa teks.',
            'npk.max' => 'NPK maksimal 30 karakter.',
            'nuptk.string' => 'NUPTK harus berupa teks.',
            'nuptk.max' => 'NUPTK maksimal 30 karakter.',
            'nama_gelar_depan.string' => 'Gelar depan harus berupa teks.',
            'nama_gelar_depan.max' => 'Gelar depan maksimal 50 karakter.',
            'nama_gelar_belakang.string' => 'Gelar belakang harus berupa teks.',
            'nama_gelar_belakang.max' => 'Gelar belakang maksimal 50 karakter.',
            'tempat_lahir.string' => 'Tempat lahir harus berupa teks.',
            'tempat_lahir.max' => 'Tempat lahir maksimal 100 karakter.',
            'tanggal_lahir.date' => 'Tanggal lahir tidak valid.',
            'status_perkawinan.in' => 'Status perkawinan harus lajang atau menikah.',
            'kontak_wa_hp.string' => 'Nomor HP/WA harus berupa teks.',
            'kontak_wa_hp.max' => 'Nomor HP/WA maksimal 30 karakter.',
            'kontak_email.email' => 'Format email tidak valid.',
            'kontak_email.max' => 'Email maksimal 100 karakter.',
            'nomor_rekening.string' => 'Nomor rekening harus berupa teks.',
            'nomor_rekening.max' => 'Nomor rekening maksimal 50 karakter.',
            'rekening_atas_nama.string' => 'Rekening atas nama harus berupa teks.',
            'rekening_atas_nama.max' => 'Rekening atas nama maksimal 100 karakter.',
            'bank_rekening.string' => 'Bank harus berupa teks.',
            'bank_rekening.max' => 'Bank maksimal 50 karakter.',

            // Alamat
            'alamat_provinsi.string' => 'Provinsi harus berupa teks.',
            'alamat_provinsi.max' => 'Provinsi maksimal 100 karakter.',
            'alamat_kabupaten.string' => 'Kabupaten/Kota harus berupa teks.',
            'alamat_kabupaten.max' => 'Kabupaten/Kota maksimal 100 karakter.',
            'alamat_kecamatan.string' => 'Kecamatan harus berupa teks.',
            'alamat_kecamatan.max' => 'Kecamatan maksimal 100 karakter.',
            'alamat_kelurahan.string' => 'Kelurahan/Desa harus berupa teks.',
            'alamat_kelurahan.max' => 'Kelurahan/Desa maksimal 100 karakter.',
            'alamat_rt.string' => 'RT harus berupa teks.',
            'alamat_rt.max' => 'RT maksimal 10 karakter.',
            'alamat_rw.string' => 'RW harus berupa teks.',
            'alamat_rw.max' => 'RW maksimal 10 karakter.',
            'alamat_kode_pos.string' => 'Kode pos harus berupa teks.',
            'alamat_kode_pos.max' => 'Kode pos maksimal 10 karakter.',
            'alamat_lengkap.string' => 'Alamat lengkap harus berupa teks.',
            'alamat_lengkap.max' => 'Alamat lengkap maksimal 255 karakter.',
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
