<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
   {
      Schema::create('rifas', function (Blueprint $table) {
          $table->id();
          $table->string('titulo');
          $table->text('descricao')->nullable();
          $table->decimal('valor_numero', 8, 2); // Ex: 7.50
          $table->integer('total_numeros');
          $table->string('status')->default('ativa'); // ativa, finalizada
          $table->string('imagem_banner')->nullable();
          $table->timestamps();
      });
   }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rifas');
    }
};
