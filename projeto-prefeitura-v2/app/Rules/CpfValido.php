<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class CpfValido implements Rule
{
    public function passes($attribute, $value)
    {
        // Remove caracteres não numéricos do CPF
        $cpf = preg_replace('/[^0-9]/', '', (string) $value);

        // Verifica se todos os dígitos são iguais, o que tornaria o CPF inválido
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        // Calcula os dígitos verificadores
        $digito1 = 0;
        $digito2 = 0;

        for ($i = 0, $x = 10; $i <= 8; $i++, $x--) {
            $digito1 += $cpf[$i] * $x;
        }

        for ($i = 0, $x = 11; $i <= 9; $i++, $x--) {
            $digito2 += $cpf[$i] * $x;
        }

        $rest1 = ($digito1 * 10) % 11;
        $rest2 = ($digito2 * 10) % 11;

        if (($rest1 == 10 ? 0 : $rest1) != $cpf[9] || ($rest2 == 10 ? 0 : $rest2) != $cpf[10]) {
            return false;
        }

        return true;
    }

    public function message()
    {
        return 'O CPF informado não é válido.';
    }
}