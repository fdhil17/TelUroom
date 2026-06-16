<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReservasiRequest extends FormRequest
{
    use \App\Traits\HasRoleImpersonation;

    public function authorize(): bool
    {
        return $this->getActiveRole() === 'mahasiswa';
    }

    public function rules(): array
    {
        return [
            'ruangan_id' => ['required', 'exists:ruangans,id'],
            'tanggal_reservasi' => ['required', 'date', 'after_or_equal:today'],
            'jam_mulai' => [
                'required',
                'date_format:H:i',
                function ($attribute, $value, $fail) {
                    $tanggalReservasi = $this->input('tanggal_reservasi');
                    if ($tanggalReservasi === now()->format('Y-m-d')) {
                        if ($value <= now()->format('H:i')) {
                            $fail('Jam mulai peminjaman untuk hari ini tidak boleh kurang dari atau sama dengan waktu saat ini.');
                        }
                    }
                },
            ],
            'jam_selesai' => ['required', 'date_format:H:i', 'after:jam_mulai'],
            'keperluan' => ['required', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'ruangan_id.required' => 'Ruangan wajib dipilih.',
            'ruangan_id.exists' => 'Ruangan tidak valid.',
            'tanggal_reservasi.required' => 'Tanggal peminjaman wajib diisi.',
            'tanggal_reservasi.after_or_equal' => 'Tanggal peminjaman tidak boleh di masa lalu.',
            'jam_mulai.required' => 'Jam mulai wajib diisi.',
            'jam_selesai.required' => 'Jam selesai wajib diisi.',
            'jam_selesai.after' => 'Jam selesai harus lebih besar dari jam mulai.',
            'keperluan.required' => 'Keperluan wajib diisi.',
        ];
    }
}
