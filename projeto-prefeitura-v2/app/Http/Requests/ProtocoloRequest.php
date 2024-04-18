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
            'pessoa_id' => ['required'],
            'files.*' => ['mimes:jpg,jpeg,pdf,png', 'max:3072', 'file'],
            'files' => ['max:5'],
            'departamento_id' => ['required']
        ];
    }

    public function messages(): array 
    {
        return [
            'descricao.required' => 'A descrição deve ser preenchida',
            'data_registro.required' => 'A data de registro deve ser preenchida',
            'prazo.required' => 'O prazo deve ser informado',
            'prazo.between' => 'O prazo deve ser de 5 a 30 dias',
            'pessoa_id.required' => 'O campo contribuinte deve ser preechido',
            'files.max' => 'A quantidade de arquivos anexados deve ser menor ou igual a 5',
            'files.*.mimes' => 'A extenção do arquivo deve ser jpg, jpeg, png ou pdf',
            'files.*.max' => 'O tamanho individual dos arquivos não deve passar de 3MB',
            'departamento_id.required' => 'O departamento deve ser informado' 
        ];
    }
}
