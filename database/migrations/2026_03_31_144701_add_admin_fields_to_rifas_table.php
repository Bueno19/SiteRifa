<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rifas', function (Blueprint $table) {
            if (! Schema::hasColumn('rifas', 'whatsapp')) {
                $table->string('whatsapp')->nullable()->after('imagem_banner');
            }

            if (! Schema::hasColumn('rifas', 'data_sorteio')) {
                $table->date('data_sorteio')->nullable()->after('whatsapp');
            }
        });
    }

    public function down(): void
    {
        Schema::table('rifas', function (Blueprint $table) {
            if (Schema::hasColumn('rifas', 'data_sorteio')) {
                $table->dropColumn('data_sorteio');
            }

            if (Schema::hasColumn('rifas', 'whatsapp')) {
                $table->dropColumn('whatsapp');
            }
        });
    }
};