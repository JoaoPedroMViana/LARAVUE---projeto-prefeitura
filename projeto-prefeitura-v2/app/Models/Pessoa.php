<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pessoa extends Model
{ 
    use HasFactory;

    protected $fillable = [
        'nome', 
        'data_nascimento', 
        'CPF', 
        'sexo', 
        'cidade', 
        'bairro', 
        'rua', 
        'numero', 
        'complemento'
    ];
    
    protected $guarded = [];
    protected $dates = ['data_nascimento'];

    public function protocolos() {
        return $this->hasMany('App\Models\Protocolo'); 
    }
}
