<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aluno extends Model
{
    use HasFactory;

    protected $fillable = ['cpf', 'user_id'];

    public function perfil(){
        return $this->hasOne(Perfil::class);
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function requisicao_documento(){
        return $this->hasMany(Requisicao_documento::class);
    }

    public function requisicoes(){
        return $this->hasMany(Requisicao::class);
    }

    public function fichasCatalograficas()
    {
        return $this->hasMany(FichaCatalografica::class, 'aluno_id');
    }

}
