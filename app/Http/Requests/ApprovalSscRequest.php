<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ApprovalSscRequest extends FormRequest
{
    use \App\Traits\HasRoleImpersonation;

    public function authorize(): bool
    {
        return $this->getActiveRole() === 'ssc';

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
            'catatan_ssc.required_if' => 'Catatan wajib diisi jika menolak peminjaman.',
        ];
    }
}
