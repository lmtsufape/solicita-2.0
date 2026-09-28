<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoDocumento extends Model
{

    protected $fillable = ['tipo'];
    use HasFactory;

    public function ficha_catalografica(){
        return $this->belongsTo(FichaCatalografica::class);
    }

}
