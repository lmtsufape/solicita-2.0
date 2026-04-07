<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContaReativada extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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

    //envia o email para o email de notifiable
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('[Solicita: Sua conta foi reativada]')
            ->greeting('Olá, ' . $notifiable->name)
            ->line('Sua conta foi reativada e já pode ser utilizada novamente.')
            ->salutation('Atenciosamente')
            ->line('---')
            ->line('Este é um e-mail automático. Por favor, não responda.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
