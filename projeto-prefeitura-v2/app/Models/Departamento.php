<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Departamento extends Model implements Auditable
{ 
    use HasFactory, \OwenIt\Auditing\Auditable;

    protected $fillable = ['nome'];

    public function protocolos() {
        return $this->hasMany('App\Models\Protocolo');
    }
}
