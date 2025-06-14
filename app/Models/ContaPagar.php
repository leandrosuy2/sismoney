<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContaPagar extends Model
{
    use HasFactory;

    protected $table = 'conta_pagars';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'empresa',
        'tipo',
        'valor',
        'data_pagamento',
        'status',
        'telefone',
        'idUsuario'
    ];
}
