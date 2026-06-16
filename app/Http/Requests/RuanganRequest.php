<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RuanganRequest extends FormRequest
{
    use \App\Traits\HasRoleImpersonation;

    public function authorize(): bool
    {
        return $this->getActiveRole() === 'logistik';
    }

    public function rules(): array
    {
        $ruanganId = $this->route('ruangan')?->id;

        return [
            'kode_ruangan' => [
                'required',
                'string',
                'max:10',
                Rule::unique('ruangans', 'kode_ruangan')->ignore($ruanganId),
            ],
            'nama_ruangan' => ['required', 'string', 'max:100'],
            'lantai' => ['required', 'integer', 'min:1', 'max:10'],
            'kapasitas' => ['required', 'integer', 'min:1'],
            'status' => ['required', Rule::in(['tersedia', 'maintenance'])],
        ];
    }

    public function messages(): array
    {
        return [
            'kode_ruangan.required' => 'Kode ruangan wajib diisi.',
            'kode_ruangan.unique' => 'Kode ruangan sudah digunakan.',
            'nama_ruangan.required' => 'Nama ruangan wajib diisi.',
            'lantai.required' => 'Lantai wajib diisi.',
            'kapasitas.required' => 'Kapasitas wajib diisi.',
            'status.required' => 'Status wajib dipilih.',
        ];
    }
}
