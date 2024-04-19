<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AcompanhamentoRequest extends FormRequest
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
            'descricao' => ['required', 'string','max:2000', 'min:250'],
            'situacao' => ['required']
        ];
    }

    public function messages(): array
    {
        return [
            'descricao.required' => 'A descrição deve ser preenchida',
            'descricao.min' => 'A descrição deve ter no mínimo 250 caracteres',
            'descricao.max' => 'A descrição deve ter no máximo 2000 caracteres',
            'situacao' => 'A situação deve ser informada'
        ];
    }
}
