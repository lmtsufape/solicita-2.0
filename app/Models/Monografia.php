<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Monografia extends Model
{
    use HasFactory;

    protected $fillable = ['nome_orientador', 'sobrenome_orientador', 'nome_coorientador',
        'sobrenome_coorientador', 'curso', 'campus', 'tipo_curso', 'nome_segundo_coorientador',
        'sobrenome_segundo_coorientador', 'ficha_catalografica_id'];

    public function fichaCatalografica()
    {
        return $this->belongsTo(FichaCatalografica::class);
    }
}
