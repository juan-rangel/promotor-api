<?php

namespace App\Notifications;

use App\Mail\EnvioAvisoProdutosRuptura as Mailable;
use App\Usuario;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class LojaComRuptura extends Notification implements ShouldQueue
{
    use Queueable;

    private $detalhes;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($dados)
    {
        $this->detalhes = $dados;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new Mailable($this->detalhes))->to($notifiable->email);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray(Usuario $notifiable)
    {
        return [
            ['usuario' => $notifiable->nome]
        ];
    }
}
