<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    //
    protected $fillable = ['nome'];

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
        return $this->hasMany('App\Models\Perfil');
    }

    public function unidade(){
        return $this->belongsTo('App\Models\Unidade');
    }
}
