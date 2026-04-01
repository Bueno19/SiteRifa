<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rifa extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo', 'descricao', 'valor_numero', 'total_numeros', 'status', 'imagem_banner'
    ];

    public function numeros()
    {
        return $this->hasMany(Numero::class);
    }

    public function reservas()
    {
        return $this->hasMany(Reserva::class);
    }
}