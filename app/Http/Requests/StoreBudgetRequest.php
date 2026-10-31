<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreBudgetRequest extends FormRequest
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
            'month' => ['required', 'date_format:Y-m'],
            'amount' => ['required', 'numeric', 'integer', 'gt:0'],
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
            'month.required' => 'Bulan anggaran wajib dipilih.',
            'month.date_format' => 'Format bulan anggaran harus YYYY-MM.',
            'amount.required' => 'Nominal batas anggaran wajib diisi.',
            'amount.numeric' => 'Nominal batas anggaran harus berupa angka.',
            'amount.integer' => 'Nominal batas anggaran harus berupa bilangan bulat Rupiah.',
            'amount.gt' => 'Nominal batas anggaran harus lebih besar dari nol.',
        ];
    }
}
