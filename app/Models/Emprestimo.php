<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Emprestimo extends Model
{
    use HasFactory;

    protected $table = 'emprestimos';

    protected $fillable = [
        'nome',
        'valor',
        'juros',
        'valor_jurosdiarios',
        'meses',
        'dataPagamento',
        'status',
        'idUsuario',
        'telefone'
    ];

    protected $casts = [
        'valor' => 'decimal:2',
        'juros' => 'decimal:2',
        'valor_jurosdiarios' => 'decimal:2',
        'meses' => 'integer',
        'dataPagamento' => 'date',
        'idUsuario' => 'integer'
    ];
}
