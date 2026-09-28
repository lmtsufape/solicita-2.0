<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Instituicao extends Model
{

    protected $fillable = ['nome', 'administrador_id'];



    public function administrador(){

        return $this->belongsTo(Administrador::class);

    }

    public function unidade(){

        return $this->hasMany(Unidade::class);
    }


}
