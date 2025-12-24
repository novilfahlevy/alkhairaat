<?php

namespace App\Http\Requests;

use App\Models\Sekolah;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSekolahRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->hasRole(['superuser', 'pengurus_besar', 'komisariat_wilayah']) && 
               auth()->user()->canAccessSekolah($this->route('sekolah')->id);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $sekolahId = $this->route('sekolah')->id;

        return [
            'kode_sekolah' => ['required', 'string', 'max:20', Rule::unique('sekolah', 'kode_sekolah')->ignore($sekolahId)],
            'nama' => ['required', 'string', 'max:255'],
            'jenis_sekolah' => ['required', Rule::in(array_keys(Sekolah::JENIS_SEKOLAH_OPTIONS))],
            'bentuk_pendidikan' => ['required', Rule::in(array_keys(Sekolah::BENTUK_PENDIDIKAN_OPTIONS))],
            'status' => ['required', Rule::in(Sekolah::STATUS_OPTIONS)],
            'id_provinsi' => ['required', 'exists:provinsi,id'],
            'id_kabupaten' => ['required', 'exists:kabupaten,id'],
            'kecamatan' => ['nullable', 'string', 'max:100'],
            'alamat' => ['nullable', 'string'],
            'telepon' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'website' => ['nullable', 'url', 'max:255'],
            'keterangan' => ['nullable', 'string'],
            'bank_rekening' => ['nullable', 'string', 'max:100'],
            'nomor_rekening' => ['nullable', 'string', 'max:50'],
            'rekening_atas_nama' => ['nullable', 'string', 'max:255'],
            'alamat_kecamatan' => ['nullable', 'string', 'max:100'],
            'alamat_kelurahan' => ['nullable', 'string', 'max:100'],
            'alamat_rt' => ['nullable', 'string', 'max:10'],
            'alamat_rw' => ['nullable', 'string', 'max:10'],
            'alamat_kode_pos' => ['nullable', 'string', 'max:10'],
            'alamat_koordinat_x' => ['nullable', 'numeric'],
            'alamat_koordinat_y' => ['nullable', 'numeric'],
            'galeri_files' => ['nullable', 'array'],
            'galeri_files.*' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'],
            'deleted_galeri_ids' => ['nullable', 'array'],
            'deleted_galeri_ids.*' => ['nullable', 'integer'],
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
            'kode_sekolah.required' => 'Kode sekolah harus diisi.',
            'kode_sekolah.unique' => 'Kode sekolah sudah digunakan.',
            'nama.required' => 'Nama sekolah harus diisi.',
            'jenis_sekolah.required' => 'Jenis sekolah harus dipilih.',
            'jenis_sekolah.in' => 'Jenis sekolah yang dipilih tidak valid.',
            'bentuk_pendidikan.required' => 'Bentuk pendidikan harus dipilih.',
            'bentuk_pendidikan.in' => 'Bentuk pendidikan yang dipilih tidak valid.',
            'status.required' => 'Status harus dipilih.',
            'status.in' => 'Status yang dipilih tidak valid.',
            'id_provinsi.required' => 'Provinsi harus dipilih.',
            'id_provinsi.exists' => 'Provinsi yang dipilih tidak valid.',
            'id_kabupaten.required' => 'Kabupaten harus dipilih.',
            'id_kabupaten.exists' => 'Kabupaten yang dipilih tidak valid.',
            'email.email' => 'Format email tidak valid.',
            'alamat_koordinat_x.numeric' => 'Koordinat X harus berupa angka.',
            'alamat_koordinat_y.numeric' => 'Koordinat Y harus berupa angka.',
            'galeri_files.array' => 'Galeri harus berupa array file.',
            'galeri_files.*.image' => 'File harus berupa gambar.',
            'galeri_files.*.mimes' => 'Format file harus JPEG, PNG, JPG, GIF, atau WebP.',
            'galeri_files.*.max' => 'Ukuran file tidak boleh lebih dari 5MB.',
            'deleted_galeri_ids.array' => 'ID galeri yang dihapus harus berupa array.',
            'deleted_galeri_ids.*.integer' => 'ID galeri harus berupa angka.',
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
            'kode_sekolah' => 'kode sekolah',
            'nama' => 'nama sekolah',
            'jenis_sekolah' => 'jenis sekolah',
            'bentuk_pendidikan' => 'bentuk pendidikan',
            'status' => 'status',
            'id_kabupaten' => 'kabupaten',
            'kecamatan' => 'kecamatan',
            'alamat' => 'alamat',
            'telepon' => 'telepon',
            'email' => 'email',
            'website' => 'website',
            'keterangan' => 'keterangan',
            'bank_rekening' => 'bank',
            'nomor_rekening' => 'nomor rekening',
            'rekening_atas_nama' => 'nama pemilik rekening',
            'alamat_kecamatan' => 'kecamatan alamat',
            'alamat_kelurahan' => 'kelurahan alamat',
            'alamat_rt' => 'RT alamat',
            'alamat_rw' => 'RW alamat',
            'alamat_kode_pos' => 'kode pos alamat',
            'alamat_koordinat_x' => 'koordinat X',
            'alamat_koordinat_y' => 'koordinat Y',
        ];
    }
}
