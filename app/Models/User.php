<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\VerifyApiEmail;

use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements MustVerifyEmail, JWTSubject
{
    use Notifiable;

    // public function sendEmailVerificationNotification()
    // {
    //       $this->notify(new App\Notifications\VerificarEmail);
    // }
    // Enviar email traduziado
    // public function sendEmailVerificationNotification()
    // {
    //     // $this->notify(new App\Notifications\CustomVerifyEmail);
    // }
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'tipo','pending_email',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public const TIPO = [
        'servidor' => 'servidor',
        'administrador' => 'administrador',
        'aluno' => 'aluno',
        'bibliotecario' => 'bibliotecario',
    ];


    public function administrador(){
        return $this->hasOne('App\Models\Administrador');
    }
    public function servidor(){
        return $this->hasOne('App\Models\Servidor');
    }
    public function bibliotecario(){
        return $this->hasOne('App\Models\Bibliotecario');
    }
    public function aluno(){
        return $this->hasOne('App\Models\Aluno');
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
    public function sendApiEmailVerificationNotification()
    {
        $this->notify(new VerifyApiEmail); // my notification
    }

    public function processos(){
        return $this->hasMany(Processo::class);
    }

     public function routeNotificationForMail($notification)
     {
        if($this->pending_email){
            return $this->pending_email;
        }
        return $this->email;
     }

}
