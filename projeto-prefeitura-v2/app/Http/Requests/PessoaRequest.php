<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Pessoa;
use Illuminate\Validation\Rule;

class PessoaRequest extends FormRequest
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
            'nome' => ['required', 'string', 'max:255', 'min:8'],
            'CPF' => ['required', 'string', 'max:14', 'min:14']
        ];
    }

    public function messages(): array
    {

        return [
            'nome.required' => 'O nome deve ser preechido',
            'nome.min' => 'O nome completo deve ser informado',
            'CPF.required' => 'O CPF deve ser preechido',
            'CPF.min' => 'O CPF está incompleto'
        ];

    }
}
