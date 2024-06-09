<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class CreateUserSeeder extends Seeder
{

    public function run()
    {
        $user = new User();
        return $user->create([
            'name' => 'Colaborador',
            'cpf' => '12345678909',
            'email' => 'teste@chamados.com',
            'type' => 'A',
            'password' => bcrypt('123456')
        ]);
    }
}
