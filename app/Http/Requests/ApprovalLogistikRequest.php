<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ApprovalLogistikRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();

        if ($user->role === 'logistik') {
            return true;
        }

        // Admin yang login sebagai Logistik
        if ($user->role === 'admin' && $this->session()->get('admin_role') === 'logistik') {
            return true;
        }

        return false;
    }

    public function rules(): array
    {
        return [
            'action' => ['required', Rule::in(['approve', 'reject'])],
            'catatan_logistik' => ['nullable', 'string', 'max:500', 'required_if:action,reject'],
        ];
    }

    public function messages(): array
    {
        return [
            'action.required' => 'Aksi wajib dipilih.',
            'catatan_logistik.required_if' => 'Catatan wajib diisi jika menolak reservasi.',
        ];
    }
}
