<?php

namespace App\Http\Requests;

use App\Models\Category;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class UpdateCategoryRequest extends FormRequest
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
            'type' => ['required', 'in:income,expense'],
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @return array<int, callable(Validator): void>
     */
    public function after(): array
    {
        return [
            function (Validator $validator): void {
                /** @var Category|null $category */
                $category = $this->route('category');

                if ($category && $category->transactions()->exists() && $this->input('type') !== $category->type) {
                    $validator->errors()->add(
                        'type',
                        'Tipe kategori tidak dapat diubah karena sudah memiliki transaksi terkait.'
                    );
                }
            },
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
            'name.required' => 'Nama kategori wajib diisi.',
            'name.max' => 'Nama kategori maksimal 50 karakter.',
            'type.required' => 'Tipe kategori wajib dipilih.',
            'type.in' => 'Tipe kategori harus berupa pemasukan (income) atau pengeluaran (expense).',
        ];
    }
}
