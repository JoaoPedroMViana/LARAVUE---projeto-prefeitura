<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Acompanhamento extends Model implements Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable;

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
