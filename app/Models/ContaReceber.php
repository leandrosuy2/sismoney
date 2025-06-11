<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContaReceber extends Model
{
    protected $table = 'conta_recebers';
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
