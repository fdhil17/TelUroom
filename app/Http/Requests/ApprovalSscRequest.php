<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ApprovalSscRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();

        if ($user->role === 'ssc') {
            return true;
        }

        // Admin yang login sebagai SSC
        if ($user->role === 'admin' && $this->session()->get('admin_role') === 'ssc') {
            return true;
        }

        return false;
    }

    public function rules(): array
    {
        return [
            'action' => ['required', Rule::in(['approve', 'reject'])],
            'catatan_ssc' => ['nullable', 'string', 'max:500', 'required_if:action,reject'],
        ];
    }

    public function messages(): array
    {
        return [
            'action.required' => 'Aksi wajib dipilih.',
            'catatan_ssc.required_if' => 'Catatan wajib diisi jika menolak reservasi.',
        ];
    }
}
