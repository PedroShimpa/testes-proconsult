<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
		$this->call(CreateUserSeeder::class);		
        Artisan::call('jwt:secret');
    }
}
