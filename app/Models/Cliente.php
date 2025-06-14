<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'users';
    protected $primaryKey = 'idUsuario';
    public $timestamps = true;

    protected $fillable = [
        'name',
        'email',
        'telefone',
        'tipo'
    ];

    public function contasReceber()
    {
        return $this->hasMany(ContaReceber::class, 'idUsuario', 'idUsuario');
    }
}
