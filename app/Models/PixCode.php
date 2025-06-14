<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PixCode extends Model
{
    use HasFactory;

    protected $table = 'pix_codes';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'pix_id',
        'conta_receber_id',
        'valor',
        'empresa'
    ];

    public function contaReceber()
    {
        return $this->belongsTo(ContaReceber::class);
    }
}
