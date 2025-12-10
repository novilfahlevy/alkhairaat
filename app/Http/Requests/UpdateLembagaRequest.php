<?php

namespace App\Http\Requests;

use App\Models\Lembaga;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLembagaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('manage_lembaga') && 
               auth()->user()->canAccessLembaga($this->route('lembaga')->id);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $lembagaId = $this->route('lembaga')->id;

        return [
            'kode_lembaga' => ['required', 'string', 'max:20', Rule::unique('lembaga', 'kode_lembaga')->ignore($lembagaId)],
            'nama' => ['required', 'string', 'max:255'],
            'jenjang' => ['required', Rule::in(Lembaga::JENJANG_OPTIONS)],
            'status' => ['required', Rule::in(Lembaga::STATUS_OPTIONS)],
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
            'kode_lembaga.required' => 'Kode lembaga harus diisi.',
            'kode_lembaga.unique' => 'Kode lembaga sudah digunakan.',
            'nama.required' => 'Nama lembaga harus diisi.',
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
            'kode_lembaga' => 'kode lembaga',
            'nama' => 'nama lembaga',
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
