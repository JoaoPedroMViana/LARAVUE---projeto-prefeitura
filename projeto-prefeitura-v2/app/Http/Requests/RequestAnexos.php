<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RequestAnexos extends FormRequest
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
            'files' => ['max:5'],
            'files.*' => ['file', 'max:3072', 'mimes:jpg,jpeg,png,pdf']
        ];
    }

    public function messages(): array
    {
        return [
            'files.max' => 'A quantidade de arquivos anexados deve ser menor ou igual a 5',
            'files.*.mimes' => 'A extenção do arquivo deve ser jpg, jpeg, png ou pdf',
            'files.*.max' => 'O tamanho individual dos arquivos não deve passar de 3MB'
        ];
    }
}
