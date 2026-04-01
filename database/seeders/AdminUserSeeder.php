<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $admins = [
            [
                'name' => 'Felipe Bueno',
                'email' => 'felipe@rifagamer.com',
                'password' => 'Felipe112@',
            ],
            [
                'name' => 'Matheus',
                'email' => 'matheus@rifagamer.com',
                'password' => 'MatheusX1!@',
            ],
            [
                'name' => 'Pedro',
                'email' => 'Pedro@rifagamer.com',
                'password' => 'PedroH@!',
            ],
            [
                'name' => 'Vinicius',
                'email' => 'Vinicius@rifagamer.com',
                'password' => 'Vinicius16B@!',
            ],
        ];

        foreach ($admins as $admin) {
            User::updateOrCreate(
                ['email' => $admin['email']],
                [
                    'name' => $admin['name'],
                    'password' => Hash::make($admin['password']),
                    'role' => 'admin',
                ]
            );
        }
    }
}