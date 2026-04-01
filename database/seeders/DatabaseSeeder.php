<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Aqui nós avisamos o Laravel para rodar o nosso arquivo que cria a Rifa e os 3500 números
        $this->call([
            RifaSeeder::class,
        ]);
    }
}