<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Inventario extends Model
{
    protected $table = 'inventarios';

    protected $fillable = [
        'armazem_id', 'data_inicio', 'data_fim',
        'status', 'usuario_responsavel_id', 'observacoes',
    ];

    protected $casts = [
        'data_inicio' => 'datetime',
        'data_fim'    => 'datetime',
    ];

    public function armazem(): BelongsTo
    {
        return $this->belongsTo(Armazem::class, 'armazem_id');
    }

    public function contagens(): HasMany
    {
        return $this->hasMany(ContagemInventario::class, 'inventario_id');
    }
}
