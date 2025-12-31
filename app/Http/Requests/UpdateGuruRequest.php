<?php

namespace App\Http\Requests;

use App\Models\Guru;
use Illuminate\Foundation\Http\FormRequest;

class UpdateGuruRequest extends FormRequest
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
            // Data Guru - wajib fields
            'nama' => 'required|string|max:100',
            'nik' => 'required|string|max:16|unique:guru,nik,' . $this->route('guru')->id,
            'jenis_kelamin' => 'required|in:L,P',
            'status' => 'required|in:aktif,tidak',
            'jenis_jabatan' => 'required|in:Kepala Sekolah,Wakil Kepala Sekolah,Guru,Staff / TU,Pengasuh Asrama',

            // Data Guru - opsional fields
            'nama_gelar_depan' => 'nullable|string|max:50',
            'nama_gelar_belakang' => 'nullable|string|max:50',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'status_perkawinan' => 'nullable|in:lajang,menikah',
            'status_kepegawaian' => 'nullable|in:PNS,Non PNS,PPPK',
            'npk' => 'nullable|string|max:20',
            'nuptk' => 'nullable|string|max:16',
            'kontak_wa_hp' => 'nullable|string|max:20',
            'kontak_email' => 'nullable|email|max:100',
            'nomor_rekening' => 'nullable|string|max:50',
            'rekening_atas_nama' => 'nullable|string|max:100',
            'bank_rekening' => 'nullable|string|max:50',

            // JabatanGuru
            'keterangan_jabatan' => 'nullable|string|max:255',

            // Alamat Asli
            'alamat_asli_provinsi' => 'nullable|string|max:100',
            'alamat_asli_kabupaten' => 'nullable|string|max:100',
            'alamat_asli_kecamatan' => 'nullable|string|max:100',
            'alamat_asli_kelurahan' => 'nullable|string|max:100',
            'alamat_asli_rt' => 'nullable|string|max:5',
            'alamat_asli_rw' => 'nullable|string|max:5',
            'alamat_asli_kode_pos' => 'nullable|string|max:10',
            'alamat_asli_lengkap' => 'nullable|string|max:500',
            'alamat_asli_koordinat_x' => 'nullable|numeric',
            'alamat_asli_koordinat_y' => 'nullable|numeric',

            // Alamat Domisili
            'alamat_domisili_provinsi' => 'nullable|string|max:100',
            'alamat_domisili_kabupaten' => 'nullable|string|max:100',
            'alamat_domisili_kecamatan' => 'nullable|string|max:100',
            'alamat_domisili_kelurahan' => 'nullable|string|max:100',
            'alamat_domisili_rt' => 'nullable|string|max:5',
            'alamat_domisili_rw' => 'nullable|string|max:5',
            'alamat_domisili_kode_pos' => 'nullable|string|max:10',
            'alamat_domisili_lengkap' => 'nullable|string|max:500',
            'alamat_domisili_koordinat_x' => 'nullable|numeric',
            'alamat_domisili_koordinat_y' => 'nullable|numeric',
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
            // Data Guru
            'nama' => 'Nama Lengkap',
            'nik' => 'NIK',
            'jenis_kelamin' => 'Jenis Kelamin',
            'status' => 'Status',
            'nama_gelar_depan' => 'Gelar Depan',
            'nama_gelar_belakang' => 'Gelar Belakang',
            'tempat_lahir' => 'Tempat Lahir',
            'tanggal_lahir' => 'Tanggal Lahir',
            'status_perkawinan' => 'Status Perkawinan',
            'status_kepegawaian' => 'Status Kepegawaian',
            'npk' => 'NPK',
            'nuptk' => 'NUPTK',
            'kontak_wa_hp' => 'WhatsApp / HP',
            'kontak_email' => 'Email',
            'nomor_rekening' => 'Nomor Rekening',
            'rekening_atas_nama' => 'Rekening Atas Nama',
            'bank_rekening' => 'Bank',

            // JabatanGuru
            'jenis_jabatan' => 'Jenis Jabatan',
            'keterangan_jabatan' => 'Keterangan Jabatan',

            // Alamat Asli
            'alamat_asli_provinsi' => 'Provinsi (Alamat Asli)',
            'alamat_asli_kabupaten' => 'Kabupaten/Kota (Alamat Asli)',
            'alamat_asli_kecamatan' => 'Kecamatan (Alamat Asli)',
            'alamat_asli_kelurahan' => 'Kelurahan (Alamat Asli)',
            'alamat_asli_rt' => 'RT (Alamat Asli)',
            'alamat_asli_rw' => 'RW (Alamat Asli)',
            'alamat_asli_kode_pos' => 'Kode Pos (Alamat Asli)',
            'alamat_asli_lengkap' => 'Alamat Lengkap (Alamat Asli)',
            'alamat_asli_koordinat_x' => 'Koordinat X / Latitude (Alamat Asli)',
            'alamat_asli_koordinat_y' => 'Koordinat Y / Longitude (Alamat Asli)',

            // Alamat Domisili
            'alamat_domisili_provinsi' => 'Provinsi (Alamat Domisili)',
            'alamat_domisili_kabupaten' => 'Kabupaten/Kota (Alamat Domisili)',
            'alamat_domisili_kecamatan' => 'Kecamatan (Alamat Domisili)',
            'alamat_domisili_kelurahan' => 'Kelurahan (Alamat Domisili)',
            'alamat_domisili_rt' => 'RT (Alamat Domisili)',
            'alamat_domisili_rw' => 'RW (Alamat Domisili)',
            'alamat_domisili_kode_pos' => 'Kode Pos (Alamat Domisili)',
            'alamat_domisili_lengkap' => 'Alamat Lengkap (Alamat Domisili)',
            'alamat_domisili_koordinat_x' => 'Koordinat X / Latitude (Alamat Domisili)',
            'alamat_domisili_koordinat_y' => 'Koordinat Y / Longitude (Alamat Domisili)',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            // Data Guru - Wajib
            'nama.required' => 'Nama guru wajib diisi.',
            'nama.string' => 'Nama guru harus berupa teks.',
            'nama.max' => 'Nama guru maksimal 100 karakter.',
            'nik.required' => 'NIK wajib diisi.',
            'nik.string' => 'NIK harus berupa teks.',
            'nik.max' => 'NIK maksimal 16 karakter.',
            'nik.unique' => 'NIK sudah digunakan oleh guru lain.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'jenis_kelamin.in' => 'Jenis kelamin harus Laki-laki (L) atau Perempuan (P).',
            'status.required' => 'Status wajib dipilih.',
            'status.in' => 'Status harus aktif atau tidak aktif.',
            'jenis_jabatan.required' => 'Jenis jabatan wajib dipilih.',
            'jenis_jabatan.in' => 'Jenis jabatan tidak valid.',

            // Data Guru - Opsional
            'nama_gelar_depan.string' => 'Gelar depan harus berupa teks.',
            'nama_gelar_depan.max' => 'Gelar depan maksimal 50 karakter.',
            'nama_gelar_belakang.string' => 'Gelar belakang harus berupa teks.',
            'nama_gelar_belakang.max' => 'Gelar belakang maksimal 50 karakter.',
            'tempat_lahir.string' => 'Tempat lahir harus berupa teks.',
            'tempat_lahir.max' => 'Tempat lahir maksimal 100 karakter.',
            'tanggal_lahir.date' => 'Tanggal lahir harus berupa tanggal yang valid.',
            'status_perkawinan.in' => 'Status perkawinan harus lajang atau menikah.',
            'status_kepegawaian.in' => 'Status kepegawaian harus PNS, Non PNS, atau PPPK.',
            'npk.string' => 'NPK harus berupa teks.',
            'npk.max' => 'NPK maksimal 20 karakter.',
            'nuptk.string' => 'NUPTK harus berupa teks.',
            'nuptk.max' => 'NUPTK maksimal 16 karakter.',
            'kontak_wa_hp.string' => 'Nomor HP/WA harus berupa teks.',
            'kontak_wa_hp.max' => 'Nomor HP/WA maksimal 20 karakter.',
            'kontak_email.email' => 'Format email tidak valid.',
            'kontak_email.max' => 'Email maksimal 100 karakter.',
            'nomor_rekening.string' => 'Nomor rekening harus berupa teks.',
            'nomor_rekening.max' => 'Nomor rekening maksimal 50 karakter.',
            'rekening_atas_nama.string' => 'Rekening atas nama harus berupa teks.',
            'rekening_atas_nama.max' => 'Rekening atas nama maksimal 100 karakter.',
            'bank_rekening.string' => 'Bank harus berupa teks.',
            'bank_rekening.max' => 'Bank maksimal 50 karakter.',

            // JabatanGuru
            'keterangan_jabatan.string' => 'Keterangan jabatan harus berupa teks.',
            'keterangan_jabatan.max' => 'Keterangan jabatan maksimal 255 karakter.',

            // Alamat
            'alamat_asli_provinsi.string' => 'Provinsi harus berupa teks.',
            'alamat_asli_provinsi.max' => 'Provinsi maksimal 100 karakter.',
            'alamat_asli_kabupaten.string' => 'Kabupaten/Kota harus berupa teks.',
            'alamat_asli_kabupaten.max' => 'Kabupaten/Kota maksimal 100 karakter.',
            'alamat_asli_kecamatan.string' => 'Kecamatan harus berupa teks.',
            'alamat_asli_kecamatan.max' => 'Kecamatan maksimal 100 karakter.',
            'alamat_asli_kelurahan.string' => 'Kelurahan harus berupa teks.',
            'alamat_asli_kelurahan.max' => 'Kelurahan maksimal 100 karakter.',
            'alamat_asli_rt.string' => 'RT harus berupa teks.',
            'alamat_asli_rt.max' => 'RT maksimal 5 karakter.',
            'alamat_asli_rw.string' => 'RW harus berupa teks.',
            'alamat_asli_rw.max' => 'RW maksimal 5 karakter.',
            'alamat_asli_kode_pos.string' => 'Kode pos harus berupa teks.',
            'alamat_asli_kode_pos.max' => 'Kode pos maksimal 10 karakter.',
            'alamat_asli_lengkap.string' => 'Alamat lengkap harus berupa teks.',
            'alamat_asli_lengkap.max' => 'Alamat lengkap maksimal 500 karakter.',
            'alamat_asli_koordinat_x.numeric' => 'Koordinat X harus berupa angka.',
            'alamat_asli_koordinat_y.numeric' => 'Koordinat Y harus berupa angka.',

            'alamat_domisili_provinsi.string' => 'Provinsi harus berupa teks.',
            'alamat_domisili_provinsi.max' => 'Provinsi maksimal 100 karakter.',
            'alamat_domisili_kabupaten.string' => 'Kabupaten/Kota harus berupa teks.',
            'alamat_domisili_kabupaten.max' => 'Kabupaten/Kota maksimal 100 karakter.',
            'alamat_domisili_kecamatan.string' => 'Kecamatan harus berupa teks.',
            'alamat_domisili_kecamatan.max' => 'Kecamatan maksimal 100 karakter.',
            'alamat_domisili_kelurahan.string' => 'Kelurahan harus berupa teks.',
            'alamat_domisili_kelurahan.max' => 'Kelurahan maksimal 100 karakter.',
            'alamat_domisili_rt.string' => 'RT harus berupa teks.',
            'alamat_domisili_rt.max' => 'RT maksimal 5 karakter.',
            'alamat_domisili_rw.string' => 'RW harus berupa teks.',
            'alamat_domisili_rw.max' => 'RW maksimal 5 karakter.',
            'alamat_domisili_kode_pos.string' => 'Kode pos harus berupa teks.',
            'alamat_domisili_kode_pos.max' => 'Kode pos maksimal 10 karakter.',
            'alamat_domisili_lengkap.string' => 'Alamat lengkap harus berupa teks.',
            'alamat_domisili_lengkap.max' => 'Alamat lengkap maksimal 500 karakter.',
            'alamat_domisili_koordinat_x.numeric' => 'Koordinat X harus berupa angka.',
            'alamat_domisili_koordinat_y.numeric' => 'Koordinat Y harus berupa angka.',
        ];
    }
}
