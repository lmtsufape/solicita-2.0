<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Administrador extends Model
{
    use HasFactory;

    protected $fillable = ['matricula', 'user_id'];

    public function instituicao(){

        return $this->hasOne(Instituicao::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
