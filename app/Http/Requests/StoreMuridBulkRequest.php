<?php

namespace App\Http\Requests;

use App\Models\Murid;
use App\Models\SekolahMurid;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreMuridBulkRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::user()->hasRole(['superuser', 'pengurus_besar', 'sekolah']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nama' => ['required', 'string', 'max:255'],
            'nisn' => ['required', 'string', 'max:20'],
            'nik' => ['nullable', 'string', 'max:20'],
            'tempat_lahir' => ['nullable', 'string', 'max:255'],
            'tanggal_lahir' => ['nullable', 'date'],
            'jenis_kelamin' => ['required', Rule::in(array_keys(Murid::JENIS_KELAMIN_OPTIONS))],
            'nama_ayah' => ['nullable', 'string', 'max:255'],
            'nomor_hp_ayah' => ['nullable', 'string', 'max:20'],
            'nama_ibu' => ['nullable', 'string', 'max:255'],
            'nomor_hp_ibu' => ['nullable', 'string', 'max:20'],
            'kontak_wa_hp' => ['nullable', 'string', 'max:20'],
            'kontak_email' => ['nullable', 'email', 'max:255'],
            'tahun_masuk' => ['required', 'integer', 'min:1900', 'max:' . date('Y') + 1],
            'kelas' => ['nullable', 'string', 'max:50'],
            'status_kelulusan' => ['nullable', Rule::in(array_keys(SekolahMurid::STATUS_KELULUSAN_OPTIONS))],
            'provinsi' => ['nullable', 'string', 'max:255'],
            'kabupaten' => ['nullable', 'string', 'max:255'],
            'kecamatan' => ['nullable', 'string', 'max:255'],
            'kelurahan' => ['nullable', 'string', 'max:255'],
            'rt' => ['nullable', 'string', 'max:10'],
            'rw' => ['nullable', 'string', 'max:10'],
            'kode_pos' => ['nullable', 'string', 'max:10'],
            'alamat_lengkap' => ['nullable', 'string', 'max:500'],
            'koordinat_x' => ['nullable', 'numeric'],
            'koordinat_y' => ['nullable', 'numeric'],
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
            'nama.required' => 'Nama murid wajib diisi',
            'nisn.required' => 'NISN wajib diisi',
            'jenis_kelamin.required' => 'Jenis kelamin wajib diisi',
            'jenis_kelamin.in' => 'Jenis kelamin tidak valid',
            'tahun_masuk.required' => 'Tahun masuk wajib diisi',
            'tahun_masuk.integer' => 'Tahun masuk harus berupa angka',
            'kontak_email.email' => 'Format email tidak valid',
            'tanggal_lahir.date' => 'Format tanggal lahir tidak valid',
        ];
    }
}
