<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Servidor extends Model
{
   protected $fillable = ['matricula', 'unidade_id', 'user_id'];


   public function requisicao(){
      return $this->hasMany(Requisicao::class);
   }

   public function unidade(){
      return $this->belongsTo(Unidade::class);
   }

   public function user(){
      return $this->belongsTo(User::class);
   }


}
