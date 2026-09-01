<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCashAccountRequest extends FormRequest
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

        if ($this->has('initial_balance') && !is_null($this->initial_balance)) {
            $cleaned = preg_replace('/[^\d.-]/', '', str_replace(['Rp', ' ', '.'], '', (string) $this->initial_balance));
            $mergeData['initial_balance'] = is_numeric($cleaned) ? (float) $cleaned : $cleaned;
        }

        if ($this->has('is_active')) {
            $mergeData['is_active'] = filter_var($this->is_active, FILTER_VALIDATE_BOOLEAN);
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
        $userId = auth()->id();

        return [
            'name' => 'required|string|max:100',
            'type' => [
                'required',
                'string',
                'max:50',
                Rule::exists('cash_account_types', 'code')->where(function ($query) use ($userId) {
                    $query->where('is_active', true)
                        ->where(function ($q) use ($userId) {
                            $q->whereNull('user_id')
                                ->orWhere('user_id', $userId);
                        });
                }),
            ],
            'account_number' => 'nullable|string|max:100',
            'icon' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:50',
            'initial_balance' => 'required|numeric|min:0|max:999999999999.99',
            'is_active' => 'boolean',
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
            'name.required' => 'Nama akun / dompet wajib diisi.',
            'type.required' => 'Pilih tipe akun.',
            'type.exists' => 'Tipe akun yang dipilih tidak valid atau tidak tersedia dalam katalog.',
            'initial_balance.required' => 'Saldo awal wajib diisi.',
            'initial_balance.numeric' => 'Saldo awal harus berupa angka valid.',
            'initial_balance.min' => 'Saldo awal tidak boleh bernilai negatif.',
            'initial_balance.max' => 'Nominal saldo awal tidak boleh melebihi Rp 999.999.999.999.',
        ];
    }
}
