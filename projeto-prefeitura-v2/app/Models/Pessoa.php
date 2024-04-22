<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Pessoa extends Model implements Auditable
{ 
    use HasFactory, \OwenIt\Auditing\Auditable;

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
