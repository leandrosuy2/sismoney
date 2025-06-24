<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Emprestimo extends Model
{
    use HasFactory;

    protected $table = 'emprestimos';
    public $timestamps = true;

    protected $fillable = [
        'nome',
        'valor',
        'juros',
        'valor_jurosdiarios',
        'meses',
        'dataPagamento',
        'telefone',
        'cpf',
        'idUsuario',
        'status'
    ];

    protected $casts = [
        'valor' => 'decimal:2',
        'juros' => 'decimal:2',
        'valor_jurosdiarios' => 'integer',
        'meses' => 'integer',
        'dataPagamento' => 'date'
    ];
}
