<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rule;
use App\Models\User;

class UserRequest extends FormRequest
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
            'name' => 'required|string|max:255', 
            'email' => 'required|string|email|max:255|unique:'.User::class,
            'password' => ['required', Rules\Password::defaults()],
            'cpf' => 'required|string|max:14|min:14|unique:'.User::class,
            'perfil' => 'required|max:1|min:1|'.Rule::in(['T', 'S', 'A']),
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O nome deve ser preenchido',
            'email.email' => 'E-mail inválido',
            'email.required' => 'O e-mail deve ser preenchido',
            'email.unique' => 'Este e-mail já foi cadastrado',
            'cpf.required' => 'O CPF deve ser preenchido',
            'cpf.min' => 'O CPF está incompleto',
            'cpf.unique' => 'Este CPF já foi cadastrado',
            'password.required' => 'A senha deve ser preenchida',
            'perfil.required' => 'O perfil do usuário deve ser informado',
            'perfil.in' => 'O perfil deve ser T, S ou A',
        ];
    }
}
