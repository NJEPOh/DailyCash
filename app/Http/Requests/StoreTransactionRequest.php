<?php

namespace App\Http\Requests;

use App\Models\Account;
use App\Models\Category;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreTransactionRequest extends FormRequest
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
            'date' => ['required', 'date'],
            'type' => ['required', 'in:income,expense'],
            'amount' => ['required', 'numeric', 'integer', 'gt:0'],
            'category_id' => ['required', 'exists:categories,id'],
            'account_id' => ['required', 'exists:accounts,id'],
            'description' => ['nullable', 'string', 'max:255'],
            'proof_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:2048'],
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
                if ($validator->errors()->isNotEmpty()) {
                    return;
                }

                $type = (string) $this->input('type');
                $amount = (int) $this->input('amount');
                $category = Category::find($this->input('category_id'));
                $account = Account::find($this->input('account_id'));

                if ($category && $category->type !== $type) {
                    $validator->errors()->add(
                        'category_id',
                        'Kategori yang dipilih tidak sesuai dengan tipe transaksi.'
                    );
                }

                if ($account && $type === 'expense' && $amount > $account->balance) {
                    $validator->errors()->add(
                        'amount',
                        'Nominal pengeluaran melebihi saldo rekening yang dipilih ('.$account->formatted_balance.').'
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
            'date.required' => 'Tanggal transaksi wajib diisi.',
            'date.date' => 'Format tanggal transaksi tidak valid.',
            'type.required' => 'Tipe transaksi wajib dipilih.',
            'type.in' => 'Tipe transaksi harus berupa pemasukan atau pengeluaran.',
            'amount.required' => 'Nominal transaksi wajib diisi.',
            'amount.numeric' => 'Nominal transaksi harus berupa angka.',
            'amount.integer' => 'Nominal transaksi harus berupa bilangan bulat Rupiah.',
            'amount.gt' => 'Nominal transaksi harus lebih besar dari nol.',
            'category_id.required' => 'Kategori transaksi wajib dipilih.',
            'category_id.exists' => 'Kategori yang dipilih tidak tersedia.',
            'account_id.required' => 'Rekening/dompet wajib dipilih.',
            'account_id.exists' => 'Rekening/dompet yang dipilih tidak tersedia.',
            'description.max' => 'Deskripsi maksimal 255 karakter.',
            'proof_image.image' => 'File bukti pembayaran harus berupa gambar.',
            'proof_image.mimes' => 'Format gambar bukti pembayaran harus JPG, JPEG, PNG, WEBP, atau GIF.',
            'proof_image.max' => 'Ukuran gambar bukti pembayaran maksimal 2MB.',
        ];
    }
}
