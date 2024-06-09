<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NovoChamadoMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $chamadoId;
    protected $name;

    public function __construct($chamadoId, $name)
    {
        $this->chamadoId = $chamadoId;
        $this->name = $name;
    }


    public function build()
    {
        return $this->subject('Novo Chamado')->html("<h3>Olá {$this->name}, o chamado de numero {$this->chamadoId} foi criado!</h3>");
    }
}
