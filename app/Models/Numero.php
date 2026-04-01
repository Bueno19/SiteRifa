<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Numero extends Model
{
    use HasFactory;

    protected $fillable = [
        'rifa_id', 'numero', 'status'
    ];

    public function rifa()
    {
        return $this->belongsTo(Rifa::class);
    }

    public function reservas()
    {
        return $this->belongsToMany(Reserva::class, 'numero_reserva');
    }
}