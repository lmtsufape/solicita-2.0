<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class FichaCatalografica extends Model
{
    use HasFactory;

    protected $dates = ['deleted_at'];

    protected $fillable = ['cutter', 'area_conhecimento', 'autor_nome', 'autor_sobrenome',
                            'titulo', 'subtitulo', 'local', 'ano', 'folhas', 'inclui_anexo',
                            'inclui_apendice', 'ilustracao', 'anexo', 'tipo_documento_id',
                            'aluno_id', 'nome_orientador', 'sobrenome_orientador', 'nome_coorientador', 'sobrenome_coorientador',
                            'nome_segundo_coorientador', 'sobrenome_segundo_coorientador'];

    public function tcc(){
        return $this->hasOne(Tcc::class);
    }

    public function tese(){
        return $this->hasOne(Tese::class);
    }

    public function palavrasChave(){
        return $this->hasMany(PalavraChave::class);
    }

    public function monografia(){
        return $this->hasOne(Monografia::class);
    }

    public function dissertacao(){
        return $this->hasOne(Dissertacao::class);
    }

    public function alunos(){
        return $this->belongsTo(Aluno::class, 'aluno_id');
    }

    public function tipo(){
        return $this->belongsTo(TipoDocumento::class, 'tipo_documento_id');
    }

    /**
     * Get the requisicaoDocumento associated with the FichaCatalografica
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function requisicaoDocumento(): HasOne
    {
        return $this->hasOne(Requisicao_documento::class);
    }
}
