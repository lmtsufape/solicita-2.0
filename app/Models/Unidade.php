<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unidade extends Model
{
    use HasFactory;

    protected $fillable = ['nome', 'instituicao_id'];


    public function instituicao(){
        return $this->belongsTo(Instituicao::class);
    }

    public function servidor(){
        return $this->hasMany(Servidor::class);
    }

    public function curso(){
        return $this->hasMany(Curso::class);
    }

    public function bibliotecas(){
        return $this->hasMany(Biblioteca::class);
    }
}
