<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EnvioAvisoProdutosRuptura extends Mailable
{
    use Queueable, SerializesModels;

    private $detalhes;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($detalhes)
    {
        $this->detalhes = $detalhes;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('mail.loja.produto-ruptura')
            ->subject('Alerta de produtos com ruptura')
            ->with([
                'produtos' => $this->detalhes['produtos'],
                'cliente' => $this->detalhes['cliente'],
                'usuario' => $this->detalhes['usuario'],
                'roteiro' => $this->detalhes['roteiro'],
            ]);
    }
}
