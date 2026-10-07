<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    use HasFactory;

    protected $fillable = ['nome', 'unidade_id', 'abreviatura'];

    public function scopeOrdenadoPorTipo($query)
    {
        return $query
            ->orderByRaw("
                CASE
                    WHEN nome LIKE '%Gradua%' THEN 1
                    WHEN nome LIKE '%Mestrado%' THEN 2
                    WHEN nome LIKE '%Especializa%' THEN 3
                    ELSE 4
                END
            ")
            ->orderByRaw("
                TRIM(
                    REPLACE(
                        REPLACE(
                            REPLACE(
                                REPLACE(
                                    regexp_replace(nome, '^\([^)]*\)\s*', ''),
                                    'Bacharelado em ',
                                    ''
                                ),
                                'Bacharelado ',
                                ''
                            ),
                            'Licenciatura em ',
                            ''
                        ),
                        'Licenciatura ',
                        ''
                    )
                )
            ");
    }

    public function perfil(){
        return $this->hasMany(Perfil::class);
    }

    public function unidade(){
        return $this->belongsTo(Unidade::class);
    }
}
