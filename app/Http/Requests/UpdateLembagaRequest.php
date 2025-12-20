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
        return auth()->user()->can('manage_sekolah') && 
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
            'jenjang' => ['required', Rule::in(Sekolah::JENJANG_OPTIONS)],
            'status' => ['required', Rule::in(Sekolah::STATUS_OPTIONS)],
            'kabupaten_id' => ['required', 'exists:kabupaten,id'],
            'kecamatan' => ['nullable', 'string', 'max:100'],
            'alamat' => ['nullable', 'string'],
            'telepon' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'keterangan' => ['nullable', 'string'],
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
            'jenjang.required' => 'Jenjang harus dipilih.',
            'jenjang.in' => 'Jenjang yang dipilih tidak valid.',
            'status.required' => 'Status harus dipilih.',
            'status.in' => 'Status yang dipilih tidak valid.',
            'kabupaten_id.required' => 'Kabupaten harus dipilih.',
            'kabupaten_id.exists' => 'Kabupaten yang dipilih tidak valid.',
            'email.email' => 'Format email tidak valid.',
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
            'jenjang' => 'jenjang',
            'status' => 'status',
            'kabupaten_id' => 'kabupaten',
            'kecamatan' => 'kecamatan',
            'alamat' => 'alamat',
            'telepon' => 'telepon',
            'email' => 'email',
            'keterangan' => 'keterangan',
        ];
    }
}
