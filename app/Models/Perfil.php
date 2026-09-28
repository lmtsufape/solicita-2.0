<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
// use SoftDeletes;

class Perfil extends Model
{
    use SoftDeletes;
    //
    protected $fillable = ['default','situacao', 'valor', 'aluno_id', 'unidade_id', 'curso_id'];
    protected $dates = ['deleted_at'];

    public const SITUACAO_ENUM = [
        'Matriculado',
        'Egresso',
        'Especial',
        'REMT - Regime Especial de Movimentação Temporária',
        'Desistente',
        'Matrícula Trancada',
        'Intercâmbio',
    ];

    public function requisicao(){
        return $this->hasMany(Requisicao::class);
    }

    public function aluno(){
        return $this->belongsTo(Aluno::class);
    }

    public function curso(){
        return $this->belongsTo(Curso::class);
    }

    public function unidade()
    {
        return $this->belongsTo(Unidade::class);
    }

}
