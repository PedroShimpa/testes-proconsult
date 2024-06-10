<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class TransformaEmAdminCommand extends Command
{
    protected $signature = 'gerar:colaborador {email}';

    protected $description = 'Pega um usuario pelo email e o transforma em admin/colaborador';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $email = $this->argument('email');
        $user = User::where('email', $email)->first();
        if ($user) {
            $user->type = 'A';
            $user->save();
            $this->info("O usuário {$user->email} foi transformado em admin/colaborador.");
        } else {
            $this->error("Nenhum usuário encontrado com o email {$email}.");
        }

        return 0;
    }
}
