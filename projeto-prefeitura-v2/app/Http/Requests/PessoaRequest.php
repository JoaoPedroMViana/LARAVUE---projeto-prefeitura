<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Pessoa;
use Illuminate\Validation\Rule;
use App\Rules\CpfValido;

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
            'CPF' => ['required', 'string', 'max:11', 'min:11', Rule::unique(Pessoa::class)->ignore($this->id), new CpfValido],
            'data_nascimento' => ['required'],
            'sexo' => ['required']
        ];
    }

    public function messages(): array
    {

        return [
            'nome.required' => 'O nome deve ser preenchido',
            'nome.min' => 'O nome completo deve ser informado',
            'CPF.required' => 'O CPF deve ser preenchido',
            'CPF.min' => 'O CPF está incompleto',
            'CPF.unique' => 'Este CPF já foi cadastrado',
            'data_nascimento.required' => 'A data de nascimento deve ser informada',
            'sexo.required' => 'O sexo deve ser informado'
        ];

    }
}
