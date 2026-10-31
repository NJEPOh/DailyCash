<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreAccountRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:50'],
            'balance' => ['nullable', 'numeric', 'integer', 'min:0'],
        ];
    }

    /**
     * Get custom validation messages in Indonesian.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama rekening/dompet wajib diisi.',
            'name.max' => 'Nama rekening/dompet maksimal 50 karakter.',
            'balance.numeric' => 'Saldo awal harus berupa angka.',
            'balance.integer' => 'Saldo awal harus berupa bilangan bulat Rupiah.',
            'balance.min' => 'Saldo awal tidak boleh kurang dari 0.',
        ];
    }
}
