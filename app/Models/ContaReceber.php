<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContaReceber extends Model
{
    use HasFactory;

    protected $table = 'conta_recebers';
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

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'idUsuario', 'idUsuario');
    }
}
