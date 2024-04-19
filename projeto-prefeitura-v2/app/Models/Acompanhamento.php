<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Acompanhamento extends Model
{
    use HasFactory;

    protected $fillable = [
        'descricao',
        'protocolo_id',
        'data'
    ];

    protected $dates = [
        'data'
    ];

    public function protocolo() {
        return $this->belongsTo('App\Models\Protocolo');
    }
}
