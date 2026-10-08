<?php

namespace App\Http\Requests;

use App\Models\InvestmentAllocation;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateInvestmentAllocationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->id === $this->route('allocation')?->user_id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'type' => ['required', 'string', 'in:'.InvestmentAllocation::TYPE_FINANCIAL.','.InvestmentAllocation::TYPE_SKILL],
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:100'],
            'platform' => ['nullable', 'string', 'max:100'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'date' => ['required', 'date'],
            'target_objective' => ['nullable', 'string', 'max:1000'],
            'status' => ['nullable', 'string', 'max:50'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'type.required' => 'Opsi alokasi (Finansial / Leher ke Atas) wajib dipilih.',
            'title.required' => 'Nama instrumen atau keahlian wajib diisi.',
            'category.required' => 'Kategori investasi wajib dipilih.',
            'amount.required' => 'Nominal alokasi wajib diisi.',
            'amount.min' => 'Nominal alokasi harus lebih dari 0.',
            'date.required' => 'Tanggal alokasi wajib diisi.',
        ];
    }
}
