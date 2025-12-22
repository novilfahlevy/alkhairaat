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
            'id_jenis_sekolah' => ['required', 'exists:jenis_sekolah,id'],
            'id_bentuk_pendidikan' => ['required', 'exists:bentuk_pendidikan,id'],
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
            'id_jenis_sekolah.required' => 'Jenis sekolah harus dipilih.',
            'id_jenis_sekolah.exists' => 'Jenis sekolah yang dipilih tidak valid.',
            'id_bentuk_pendidikan.required' => 'Bentuk pendidikan harus dipilih.',
            'id_bentuk_pendidikan.exists' => 'Bentuk pendidikan yang dipilih tidak valid.',
            'status.required' => 'Status harus dipilih.',
            'status.in' => 'Status yang dipilih tidak valid.',
            'id_provinsi.required' => 'Provinsi harus dipilih.',
            'id_provinsi.exists' => 'Provinsi yang dipilih tidak valid.',
            'id_kabupaten.required' => 'Kabupaten harus dipilih.',
            'id_kabupaten.exists' => 'Kabupaten yang dipilih tidak valid.',
            'email.email' => 'Format email tidak valid.',
            'alamat_koordinat_x.numeric' => 'Koordinat X harus berupa angka.',
            'alamat_koordinat_y.numeric' => 'Koordinat Y harus berupa angka.',
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
            'id_jenis_sekolah' => 'jenis sekolah',
            'id_bentuk_pendidikan' => 'bentuk pendidikan',
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
