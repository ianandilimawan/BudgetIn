<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateCashTransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        $mergeData = [];

        if ($this->has('amount') && !is_null($this->amount)) {
            $val = (string) $this->amount;
            $val = str_ireplace(['rp', ' '], '', $val);
            if (strpos($val, '.') !== false && strpos($val, ',') !== false) {
                $val = str_replace('.', '', $val);
                $val = str_replace(',', '.', $val);
            } elseif (strpos($val, '.') !== false) {
                $parts = explode('.', $val);
                if (count($parts) > 2 || (count($parts) === 2 && strlen($parts[1]) === 3)) {
                    $val = str_replace('.', '', $val);
                }
            } elseif (strpos($val, ',') !== false) {
                $val = str_replace(',', '.', $val);
            }
            $cleaned = preg_replace('/[^\d.-]/', '', $val);
            $mergeData['amount'] = is_numeric($cleaned) && $cleaned !== '' ? (float) $cleaned : 0;
        }

        // Auto-detect type from category if type is not explicitly provided
        if ($this->has('category_id') && (!$this->has('type') || empty($this->type))) {
            $category = \App\Models\TransactionCategory::find($this->category_id);
            if ($category) {
                $mergeData['type'] = $category->type;
            }
        }

        if (!empty($mergeData)) {
            $this->merge($mergeData);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $isTransfer = $this->input('type') === 'transfer';

        return [
            'account_id' => 'nullable|exists:cash_accounts,id',
            'to_account_id' => $isTransfer ? 'required|exists:cash_accounts,id|different:account_id' : 'nullable|exists:cash_accounts,id',
            'category_id' => $isTransfer ? 'nullable|exists:transaction_categories,id' : 'required|exists:transaction_categories,id',
            'type' => 'nullable|in:income,expense,transfer',
            'amount' => 'required|numeric|min:0.01|max:999999999999.99',
            'transaction_date' => 'required|date',
            'note' => 'nullable|string|max:255',
            'proof' => 'nullable|file|mimes:jpg,jpeg,png,webp,pdf,heic|max:10240',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'category_id.required' => 'Pilih kategori transaksi.',
            'category_id.exists' => 'Kategori yang dipilih tidak valid.',
            'to_account_id.required' => 'Pilih akun/dompet tujuan transfer.',
            'to_account_id.different' => 'Akun tujuan harus berbeda dari akun asal.',
            'amount.required' => 'Nominal transaksi wajib diisi.',
            'amount.numeric' => 'Nominal transaksi harus berupa angka.',
            'amount.min' => 'Nominal transaksi minimal Rp 1.',
            'amount.max' => 'Nominal transaksi tidak boleh melebihi Rp 999.999.999.999.',
            'transaction_date.required' => 'Tanggal transaksi wajib diisi.',
        ];
    }
}
