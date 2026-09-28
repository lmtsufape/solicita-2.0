<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bibliotecario extends Model
{
    use HasFactory;

    protected $fillable = ['matricula', 'crb', 'user_id', 'biblioteca_id'];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function biblioteca(){
        return $this->belongsTo(Biblioteca::class);
    }

    public function requisicao_documento(){
        return $this->hasMany(Requisicao_documento::class);
    }
}
