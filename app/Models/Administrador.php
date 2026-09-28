<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Administrador extends Model
{
    //
    protected $fillable = ['matricula'];


    public function instituicao(){

        return $this->hasOne(Instituicao::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
