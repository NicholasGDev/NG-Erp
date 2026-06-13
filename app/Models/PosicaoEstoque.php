<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PosicaoEstoque extends Model
{
    protected $table = 'posicoes_estoque';

    protected $fillable = ['armazem_id', 'corredor', 'prateleira', 'nivel', 'status'];

    public function armazem(): BelongsTo
    {
        return $this->belongsTo(Armazem::class, 'armazem_id');
    }
}
