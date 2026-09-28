<?php

namespace App\Models;

use App\Notifications\NadaConstaSolicitado;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Biblioteca extends Model
{
    use HasFactory;
    use Notifiable;

    protected $fillable = ['nome', 'unidade_id', 'email', 'email_nada_consta'];

    /**
     * Route notifications for the mail channel.
     *
     * @return  array<string, string>|string
     */
    public function routeNotificationForMail($notification)
    {
        $emails = $this->emailsArray();

        if ($notification instanceof NadaConstaSolicitado && $this->email_nada_consta != null) {
            $emails[] = $this->email_nada_consta;
        }

        return $emails;
    }

    public function emailsArray()
    {
        $emails = $this->emails->pluck('email')->toArray();
        array_push($emails, $this->email);

        return $emails;
    }

    public function emails()
    {
        return $this->hasMany(Email::class);
    }
}
