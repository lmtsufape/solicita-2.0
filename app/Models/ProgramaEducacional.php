<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgramaEducacional extends Model
{
    use HasFactory;

    protected $fillable = [
        'programa',
        'campus',
        'nome_orientador',
        'sobrenome_orientador',
        'nome_coorientador',
        'sobrenome_coorientador',
        'produto',
        'ficha_catalografica_id',
    ];

    public function fichaCatalografica(): BelongsTo
    {
        return $this->belongsTo(FichaCatalografica::class);
    }
}
