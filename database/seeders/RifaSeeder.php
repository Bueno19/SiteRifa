<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Rifa;
use App\Models\Numero;

class RifaSeeder extends Seeder
{
    public function run()
    {
        // 1. Cria a Rifa do Monitor (Agora com 4500)
        $rifa = Rifa::create([
            'titulo' => 'Rifa Gamer - Monitor ASUS ROG 360Hz',
            'descricao' => 'Participando você concorre a um monitor high-end!',
            'valor_numero' => 7.50,
            'total_numeros' => 4500, // <- Alterado aqui
            'status' => 'ativa',
        ]);

        // 2. Prepara os 4500 números
        $numeros = [];
        for ($i = 1; $i <= 4500; $i++) { // <- Alterado aqui
            $numeros[] = [
                'rifa_id' => $rifa->id,
                // O str_pad transforma o número 1 em '0001', e o 4500 em '4500'
                'numero' => str_pad($i, 4, '0', STR_PAD_LEFT), 
                'status' => 'disponivel',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // 3. Insere todos os números de uma vez no banco
        Numero::insert($numeros);
    }
}