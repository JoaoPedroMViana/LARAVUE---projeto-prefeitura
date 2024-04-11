<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Arq_anexado extends Model
{
    use HasFactory;

    protected $fillable = [
        'path',
        'protocolo_id'
    ];

    protected $guarded = [];

    public function protocolo() {
        return $this->belongsTo('App\Models\Protocolo');
    }
}
