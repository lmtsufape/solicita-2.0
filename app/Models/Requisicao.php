<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Requisicao extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    //
    protected $fillable = ['aluno_id','data_pedido','hora_pedido', 'perfil_id', 'servidor_id'];


    public function perfil(){
        return $this->belongsTo(Perfil::class);
    }

    public function aluno()
    {
        return $this->belongsTo(Aluno::class);
    }

    public function servidor(){
        return $this->belongsTo(Servidor::class);
    }

    public function requisicao_documento(){
        return $this->hasMany(Requisicao_documento::class);
    }

}
