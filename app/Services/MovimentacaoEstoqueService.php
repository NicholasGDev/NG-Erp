<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\MovimentacaoEstoque;
use App\Models\Produto;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

/**
 * Kardex — movimentações são imutáveis.
 * Nunca expõe update() ou destroy().
 */
class MovimentacaoEstoqueService
{
    public function __construct(
        protected MovimentacaoEstoque $model,
        protected Produto $produto,
    ) {}

    public function index(): LengthAwarePaginator
    {
        return $this->model
            ->with(['produto', 'armazemOrigem', 'armazemDestino'])
            ->orderByDesc('data_hora')
            ->paginate();
    }

    public function porProduto(int $produtoId): LengthAwarePaginator
    {
        return $this->model
            ->where('produto_id', $produtoId)
            ->orderByDesc('data_hora')
            ->paginate();
    }

    public function show(int $id): MovimentacaoEstoque
    {
        return $this->model
            ->with(['produto', 'lote', 'armazemOrigem', 'armazemDestino'])
            ->findOrFail($id);
    }

    public function registrar(array $data): MovimentacaoEstoque
    {
        return DB::transaction(function () use ($data) {
            $produto = $this->produto->lockForUpdate()->findOrFail($data['produto_id']);

            $saldo = $this->calcularSaldo($produto->id, $data['tipo_movimento'], (float) $data['quantidade']);

            $data['saldo_apos_movimento'] = $saldo;
            $data['data_hora']            ??= now();

            // Atualiza custo médio em entradas
            if (str_starts_with($data['tipo_movimento'], 'entrada')) {
                $custo = (float) ($data['custo_unitario_movimento'] ?? $produto->custo_medio_atual);
                $produto->update(['custo_medio_atual' => $custo]);
            }

            return $this->model->create($data);
        });
    }

    private function calcularSaldo(int $produtoId, string $tipo, float $quantidade): float
    {
        $ultima = $this->model
            ->where('produto_id', $produtoId)
            ->orderByDesc('data_hora')
            ->value('saldo_apos_movimento') ?? 0.0;

        $saidas = ['saida_venda', 'ajuste_perda'];

        return in_array($tipo, $saidas, true)
            ? $ultima - $quantidade
            : $ultima + $quantidade;
    }
}
