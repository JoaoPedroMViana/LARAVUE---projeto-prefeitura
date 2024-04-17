<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permissoe extends Model
{
    use HasFactory;

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
