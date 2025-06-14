<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    use HasFactory;

    protected $table = 'empresas';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'nome',
        'cnpj',
        'telefone',
        'email'
    ];

    public function contasReceber()
    {
        return $this->hasMany(ContaReceber::class, 'empresa', 'id');
    }
}
