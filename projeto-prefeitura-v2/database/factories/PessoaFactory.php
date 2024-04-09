<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pessoa>
 */
class PessoaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function sexo(){
        if(random_int(0, 1) == 0) return "masculino";
        else  return "feminino";
    }// gerar sexo aleatorio

    public function cpf(){
        $cpf = [];
        for($i = 0; $i <= 10; $i++){
            $cpf[$i] = (random_int(0, 9));
        }
        return vsprintf('%d%d%d.%d%d%d.%d%d%d-%d%d', $cpf);
    }// gerar cpf aleatorio

    public function definition(): array
    {
    
        return [
            // 
            'nome' => fake()->name(),
            'data_nascimento' => fake()->date('Y-m-d'),
            'CPF' => $this->cpf(),
            'sexo' => $this->sexo() 
        ];
    }
}
