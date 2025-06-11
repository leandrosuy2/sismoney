<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContaPagar extends Model
{
    protected $table = 'conta_pagars';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'empresa',
        'tipo',
        'valor',
        'data_pagamento',
        'status',
        'telefone'
    ];
}
