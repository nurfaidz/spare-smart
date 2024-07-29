<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OutgoingItemRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'spare_part_id' => ['required', 'exists:spare_parts,id'],
            'quantity' => ['required', 'numeric'],
            'outgoing_at' => ['required', 'date_format:Y-m-d'],
            'note' => ['nullable', 'string'],
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array<string, string>
     */
    public function messages()
    {
        return [
            'spare_part_id.required' => 'Spare part wajib diisi',
            'spare_part_id.exists' => 'Spare part tidak ditemukan',
            'quantity.required' => 'Jumlah wajib diisi',
            'quantity.numeric' => 'Jumlah harus berupa angka',
            'outgoing_at.required' => 'Tanggal keluar wajib diisi',
            'outgoing_at.date_format' => 'Format tanggal keluar tidak valid',
        ];
    }
}
