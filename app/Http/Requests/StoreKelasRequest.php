<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreKelasRequest extends FormRequest
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
            'namakelas' => 'required|string|max:255',
            'peryataan' => 'required|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'namakelas.required' => 'Nama kelas harus diisi',
            'namakelas.max' => 'Nama kelas maksimal 255 karakter',
            'peryataan.required' => 'Peryataan harus diisi',
            'peryataan.max' => 'Peryataan maksimal 255 karakter',
        ];
    }
}
