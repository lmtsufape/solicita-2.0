<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class FichaCatalografica extends Model
{
    use HasFactory;

    protected $dates = ['deleted_at'];

    protected $fillable = ['cutter', 'classificacao', 'autor_nome', 'autor_sobrenome',
                            'titulo', 'subtitulo', 'local', 'ano', 'folhas', 'inclui_anexo',
                            'inclui_apendice', 'ilustracao', 'anexo', 'tipo_documento_id'];

    public function tcc(){
        return $this->hasOne(Tcc::class);
    }

    public function tese(){
        return $this->hasOne(Tese::class);
    }

    public function palavra_chave(){
        return $this->hasMany(PalavraChave::class);
    }

    public function monografia(){
        return $this->hasOne(Monografia::class);
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
