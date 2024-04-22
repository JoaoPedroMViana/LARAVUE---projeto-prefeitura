<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Arq_anexado extends Model implements Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'path',
        'protocolo_id'
    ];

    protected $guarded = [];

    public function protocolo() {
        return $this->belongsTo('App\Models\Protocolo');
    }
}
