<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoFichaCatalografica extends Model
{
    protected $table = 'tipos_fichas_catalograficas';

    protected $fillable = ['nome'];
    use HasFactory;

    public function ficha_catalografica(){
        return $this->belongsTo(FichaCatalografica::class);
    }

}
