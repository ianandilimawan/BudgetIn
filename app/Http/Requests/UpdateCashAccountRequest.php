<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
            $mergeData['initial_balance'] = is_numeric($cleaned) ? (float) $cleaned : 0;
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
        return [
            'name' => 'required|string|max:100',
            'type' => 'required|string|max:50',
            'account_number' => 'nullable|string|max:100',
            'icon' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:50',
            'initial_balance' => 'required|numeric|min:0',
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
            'initial_balance.required' => 'Saldo awal wajib diisi.',
            'initial_balance.numeric' => 'Saldo awal harus berupa angka.',
        ];
    }
}
