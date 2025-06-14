<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    protected $table = 'emprestimos';

    protected $fillable = [
        'idUsuario',
        'valor',
        'dataPagamento',
        'status',
        'telefone',
        'nome'
    ];

    protected $casts = [
        'dataPagamento' => 'datetime',
        'valor' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'idUsuario');
    }

    public function getTelefoneAttribute()
    {
        return $this->user->telefone ?? null;
    }
}
