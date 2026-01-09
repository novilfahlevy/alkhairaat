<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMuridRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Data pribadi
            'nama' => 'required|string|max:255',
            'nisn' => 'required|string|size:10|unique:murid,nisn,' . $this->route('murid')->id,
            'jenis_kelamin' => 'required|in:L,P',
            'tahun_masuk' => 'required|digits:4|integer|min:1900|max:' . (date('Y') + 1),
            'nik' => 'nullable|string|max:16|unique:murid,nik,' . $this->route('murid')->id,
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date|before:today',
            'kontak_wa_hp' => 'nullable|string|max:20',
            'kontak_email' => 'nullable|email|max:255',

            // Data orang tua
            'nama_ayah' => 'nullable|string|max:255',
            'nomor_hp_ayah' => 'nullable|string|max:20',
            'nama_ibu' => 'nullable|string|max:255',
            'nomor_hp_ibu' => 'nullable|string|max:20',

            // Data sekolah & pendidikan
            'tahun_keluar' => 'nullable|digits:4|integer|min:1900|max:' . (date('Y') + 1),
            'kelas' => 'nullable|string|max:255',
            'status_kelulusan' => 'nullable|in:ya,tidak',
            'tahun_mutasi_masuk' => 'nullable|digits:4|integer|min:1900|max:' . (date('Y') + 1),
            'alasan_mutasi_masuk' => 'nullable|string|max:255',
            'tahun_mutasi_keluar' => 'nullable|digits:4|integer|min:1900|max:' . (date('Y') + 1),
            'alasan_mutasi_keluar' => 'nullable|string|max:255',

            // Alamat asli
            'alamat_asli_provinsi' => 'nullable|string|max:255',
            'alamat_asli_kabupaten' => 'nullable|string|max:255',
            'alamat_asli_kecamatan' => 'nullable|string|max:255',
            'alamat_asli_kelurahan' => 'nullable|string|max:255',
            'alamat_asli_rt' => 'nullable|string|max:10',
            'alamat_asli_rw' => 'nullable|string|max:10',
            'alamat_asli_kode_pos' => 'nullable|string|max:10',
            'alamat_asli_lengkap' => 'nullable|string|max:255',
            'alamat_asli_koordinat_x' => 'nullable|numeric',
            'alamat_asli_koordinat_y' => 'nullable|numeric',

            // Alamat domisili
            'alamat_domisili_provinsi' => 'nullable|string|max:255',
            'alamat_domisili_kabupaten' => 'nullable|string|max:255',
            'alamat_domisili_kecamatan' => 'nullable|string|max:255',
            'alamat_domisili_kelurahan' => 'nullable|string|max:255',
            'alamat_domisili_rt' => 'nullable|string|max:10',
            'alamat_domisili_rw' => 'nullable|string|max:10',
            'alamat_domisili_kode_pos' => 'nullable|string|max:10',
            'alamat_domisili_lengkap' => 'nullable|string|max:255',
            'alamat_domisili_koordinat_x' => 'nullable|numeric',
            'alamat_domisili_koordinat_y' => 'nullable|numeric',

            // Alamat ayah
            'alamat_ayah_provinsi' => 'nullable|string|max:255',
            'alamat_ayah_kabupaten' => 'nullable|string|max:255',
            'alamat_ayah_kecamatan' => 'nullable|string|max:255',
            'alamat_ayah_kelurahan' => 'nullable|string|max:255',
            'alamat_ayah_rt' => 'nullable|string|max:10',
            'alamat_ayah_rw' => 'nullable|string|max:10',
            'alamat_ayah_kode_pos' => 'nullable|string|max:10',
            'alamat_ayah_lengkap' => 'nullable|string|max:255',
            'alamat_ayah_koordinat_x' => 'nullable|numeric',
            'alamat_ayah_koordinat_y' => 'nullable|numeric',

            // Alamat ibu
            'alamat_ibu_provinsi' => 'nullable|string|max:255',
            'alamat_ibu_kabupaten' => 'nullable|string|max:255',
            'alamat_ibu_kecamatan' => 'nullable|string|max:255',
            'alamat_ibu_kelurahan' => 'nullable|string|max:255',
            'alamat_ibu_rt' => 'nullable|string|max:10',
            'alamat_ibu_rw' => 'nullable|string|max:10',
            'alamat_ibu_kode_pos' => 'nullable|string|max:10',
            'alamat_ibu_lengkap' => 'nullable|string|max:255',
            'alamat_ibu_koordinat_x' => 'nullable|numeric',
            'alamat_ibu_koordinat_y' => 'nullable|numeric',
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
            'nama' => 'Nama Lengkap',
            'nisn' => 'NISN',
            'nik' => 'NIK',
            'tempat_lahir' => 'Tempat Lahir',
            'tanggal_lahir' => 'Tanggal Lahir',
            'jenis_kelamin' => 'Jenis Kelamin',
            'kontak_wa_hp' => 'WhatsApp / HP',
            'kontak_email' => 'Email',
            'nama_ayah' => 'Nama Ayah',
            'nomor_hp_ayah' => 'Nomor HP Ayah',
            'nama_ibu' => 'Nama Ibu',
            'nomor_hp_ibu' => 'Nomor HP Ibu',
            'tahun_keluar' => 'Tahun Keluar',
            'kelas' => 'Kelas',
            'status_kelulusan' => 'Status Kelulusan',
            'tahun_mutasi_masuk' => 'Tahun Mutasi Masuk',
            'alasan_mutasi_masuk' => 'Alasan Mutasi Masuk',
            'tahun_mutasi_keluar' => 'Tahun Mutasi Keluar',
            'alasan_mutasi_keluar' => 'Alasan Mutasi Keluar',
        ];
    }

    public function messages(): array
    {
        return [
            'nisn.size' => 'NISN harus terdiri dari :size karakter.',
            'nisn.unique' => 'NISN sudah terdaftar untuk murid lain.',
            'nik.size' => 'NIK harus terdiri dari :size karakter.',
            'nik.unique' => 'NIK sudah terdaftar untuk murid lain.',
            'tanggal_lahir.before' => 'Tanggal Lahir harus berupa tanggal sebelum hari ini.',
            'tahun_masuk.digits' => 'Tahun Masuk harus terdiri dari :digits digit.',
            'tahun_masuk.min' => 'Tahun Masuk tidak boleh kurang dari :min.',
            'tahun_masuk.max' => 'Tahun Masuk tidak boleh lebih dari :max.',
        ];
    }
}
