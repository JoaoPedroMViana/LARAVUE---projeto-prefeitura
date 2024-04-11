<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Protocolo extends Model
{
    use HasFactory;

    protected $fillable = [
        'descricao',
        'data_registro',
        'prazo',
        'pessoa_id'
    ];

    protected $guarded = [];
    protected $dates = ['data_registro'];

    public function pessoa() {
        return $this->belongsTo('App\Models\Pessoa');
    }

    public function anexados() {
        return $this->hasMany('App\Models\Arq_anexado');
    }
}
