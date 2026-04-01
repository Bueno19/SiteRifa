<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reserva_numeros', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('reserva_id');
            $table->unsignedBigInteger('numero_id');
            $table->timestamps();

            $table->unique(['reserva_id', 'numero_id']);
            $table->index('reserva_id');
            $table->index('numero_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reserva_numeros');
    }
};