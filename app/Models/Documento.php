<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{

    protected $fillable = ['tipo', 'detalhes'];

    public function requisicao_documento(){
        return $this->belongsTo(Requisicao_documento::class);
    }
}
