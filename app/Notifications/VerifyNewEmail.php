<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;

class VerifyNewEmail extends Notification
{
    use Queueable;

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $verificationUrl = URL::temporarySignedRoute(
            'verify.new.email',
            Carbon::now()->addMinutes(60),
            ['id' => $notifiable->id]
        );

        return (new MailMessage)
            ->subject('Verificar novo e-mail')
            ->line('Clique no botão abaixo para verificar seu novo e-mail.')
            ->action('Verificar E-mail', $verificationUrl)
            ->line('Se você não solicitou a mudança de e-mail, ignore este e-mail.');
    }
}