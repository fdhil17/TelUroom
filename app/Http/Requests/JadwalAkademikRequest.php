<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class JadwalAkademikRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->role === 'logistik';
    }

    public function rules(): array
    {
        return [
            'ruangan_id' => ['required', 'exists:ruangans,id'],
            'hari' => ['required', Rule::in(['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu'])],
            'jam_mulai' => ['required', 'date_format:H:i'],
            'jam_selesai' => ['required', 'date_format:H:i', 'after:jam_mulai'],
            'mata_kuliah' => ['required', 'string', 'max:100'],
            'dosen' => ['required', 'string', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'ruangan_id.required' => 'Ruangan wajib dipilih.',
            'ruangan_id.exists' => 'Ruangan tidak valid.',
            'hari.required' => 'Hari wajib dipilih.',
            'jam_mulai.required' => 'Jam mulai wajib diisi.',
            'jam_selesai.required' => 'Jam selesai wajib diisi.',
            'jam_selesai.after' => 'Jam selesai harus lebih besar dari jam mulai.',
            'mata_kuliah.required' => 'Mata kuliah wajib diisi.',
            'dosen.required' => 'Nama dosen wajib diisi.',
        ];
    }
}
