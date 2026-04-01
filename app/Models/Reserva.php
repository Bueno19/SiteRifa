<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    use HasFactory;

    protected $fillable = [
        'rifa_id', 'nome', 'telefone', 'instagram', 'valor_total', 'status', 'expira_em', 'observacoes'
    ];

    protected $casts = [
        'expira_em' => 'datetime',
    ];

    public function rifa()
    {
        return $this->belongsTo(Rifa::class);
    }

    public function numeros()
    {
        return $this->belongsToMany(Numero::class, 'numero_reserva');
    }
}