<?php

namespace App\Http\Requests;

use App\Models\Murid;
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
            'murid' => ['required', 'array', 'min:1'],
            'murid.*.nama' => ['required', 'string', 'max:255'],
            'murid.*.nisn' => ['required', 'string', 'max:20', 'distinct'],
            'murid.*.nik' => ['nullable', 'string', 'max:20'],
            'murid.*.tempat_lahir' => ['nullable', 'string', 'max:255'],
            'murid.*.tanggal_lahir' => ['nullable', 'date'],
            'murid.*.jenis_kelamin' => ['required', Rule::in(array_keys(Murid::JENIS_KELAMIN_OPTIONS))],
            'murid.*.nama_ayah' => ['nullable', 'string', 'max:255'],
            'murid.*.nomor_hp_ayah' => ['nullable', 'string', 'max:20'],
            'murid.*.nama_ibu' => ['nullable', 'string', 'max:255'],
            'murid.*.nomor_hp_ibu' => ['nullable', 'string', 'max:20'],
            'murid.*.kontak_wa_hp' => ['nullable', 'string', 'max:20'],
            'murid.*.kontak_email' => ['nullable', 'email', 'max:255'],
            'murid.*.tahun_masuk' => ['required', 'integer', 'min:1900', 'max:' . date('Y') + 1],
            'murid.*.kelas' => ['nullable', 'string', 'max:50'],
            'murid.*.status_kelulusan' => ['nullable', Rule::in(array_keys(Murid::JENIS_KELAMIN_OPTIONS))],
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
            'murid.required' => 'Minimal harus ada satu baris data murid',
            'murid.array' => 'Format data murid tidak valid',
            'murid.min' => 'Minimal harus ada satu baris data murid',
            'murid.*.nama.required' => 'Nama murid wajib diisi',
            'murid.*.nisn.required' => 'NISN wajib diisi',
            'murid.*.nisn.distinct' => 'NISN tidak boleh duplikat',
            'murid.*.jenis_kelamin.required' => 'Jenis kelamin wajib diisi',
            'murid.*.jenis_kelamin.in' => 'Jenis kelamin tidak valid',
            'murid.*.tahun_masuk.required' => 'Tahun masuk wajib diisi',
            'murid.*.tahun_masuk.integer' => 'Tahun masuk harus berupa angka',
            'murid.*.kontak_email.email' => 'Format email tidak valid',
            'murid.*.tanggal_lahir.date' => 'Format tanggal lahir tidak valid',
        ];
    }
}
