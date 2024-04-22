<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Permissoe extends Model implements Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'departamento_id',
        'user_id',
        'data'
    ];

    protected $dates = [
        'created_at',
        'data'
    ];

    public function user() {
        return $this->belongsTo('App\Models\User');
    }
}
