<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kyslik\ColumnSortable\Sortable;
class Requisicao_documento extends Model
{
    use SoftDeletes;

    use Sortable;
    protected $dates = ['deleted_at'];

    public $sortable = ['status', 'autor_nome', 'entity_created_at', 'entity_updated_at'];

    protected $fillable = ['anotacoes','status','status_data','detalhes', 'aluno_id', 'documento_id',
                            'servidor_id', 'requisicao_id', 'ficha_catalografica_id', 'bibliotecario_id',
                            'nada_consta_id', 'deposito_id'];

    public function requisicao()
    {
        return $this->belongsTo(Requisicao::class, 'requisicao_id');
    }

    public function documento()
    {
        return $this->belongsTo(Documento::class, 'documento_id');
    }

    public function nadaConsta()
    {
        return $this->belongsTo(NadaConsta::class, 'nada_consta_id');
    }

    public function deposito()
    {
        return $this->belongsTo(Deposito::class, 'deposito_id');
    }

    public function fichaCatalografica()
    {
        return $this->belongsTo(FichaCatalografica::class, 'ficha_catalografica_id');
    }

    /**
     * Get all of the retificacoes for the Requisicao_documento
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function retificacoes(): HasMany
    {
        return $this->hasMany(Retificacao::class);
    }

    public function bibliotecario()
    {
        return $this->belongsTo(Bibliotecario::class, 'bibliotecario_id');
    }

    public function aluno()
    {
        return $this->belongsTo(Aluno::class, 'aluno_id');
    }
}
