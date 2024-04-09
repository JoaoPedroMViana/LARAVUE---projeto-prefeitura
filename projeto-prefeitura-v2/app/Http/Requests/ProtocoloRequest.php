<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProtocoloRequest extends FormRequest
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
            'descricao' => ['required'],
            'data_registro' => ['required'],
            'prazo' => ['required', 'numeric', 'between:5,30'],
            'pessoa_id' => ['required']
        ];
    }

    public function messages(): array 
    {
        return [
            'descricao.required' => 'A descrição deve ser preenchida',
            'data_registro.required' => 'A data de registro deve ser preenchida',
            'prazo.required' => 'O prazo deve ser informado',
            'prazo.between' => 'O prazo deve ser de 5 a 30 dias',
            'pessoa_id.required' => 'O campo contribuinte deve ser preechido'
        ];
    }
}
