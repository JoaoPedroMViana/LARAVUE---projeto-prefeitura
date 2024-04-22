<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Protocolo extends Model implements Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'descricao',
        'data_registro',
        'prazo',
        'pessoa_id',
        'departamento_id',
        'situacao'
    ];

    protected $guarded = [];
    protected $dates = ['data_registro'];

    public function pessoa() {
        return $this->belongsTo('App\Models\Pessoa');
    }

    public function anexados() {
        return $this->hasMany('App\Models\Arq_anexado');
    }

    public function departamento() {
        return $this->belongsTo('App\Models\Departamento');
    }

    public function acompanhamentos() {
        return $this->hasMany('App\Models\Acompanhamento');
    }
}
