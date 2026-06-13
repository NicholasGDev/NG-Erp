<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('movimentacoes_estoque', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produto_id')->constrained('produtos')->restrictOnDelete();
            $table->foreignId('lote_id')->nullable()->constrained('lotes')->nullOnDelete();
            $table->foreignId('armazem_origem_id')->nullable()->constrained('armazens')->nullOnDelete();
            $table->foreignId('armazem_destino_id')->nullable()->constrained('armazens')->nullOnDelete();
            $table->string('tipo_movimento')
                ->comment('entrada_compra | saida_venda | transferencia | ajuste_perda | ajuste_ganho | devolucao');
            $table->decimal('quantidade', 12, 3)
                ->comment('Usa decimal(12,3) para suportar KG, Litros, etc.');
            $table->decimal('custo_unitario_movimento', 14, 4)->default(0);
            $table->decimal('saldo_apos_movimento', 12, 3)
                ->comment('Kardex: saldo calculado apos esta movimentacao');
            $table->dateTime('data_hora');
            $table->unsignedBigInteger('usuario_id')->comment('FK para tabela de usuarios do sistema');
            $table->string('documento_referencia')->nullable()
                ->comment('NF, pedido_venda_id, ordem_transferencia_id, etc.');
            $table->text('observacao')->nullable();
            $table->timestamps();

            $table->index(['produto_id', 'data_hora']);
            $table->index(['produto_id', 'armazem_destino_id']);
            $table->index('tipo_movimento');
            $table->index('data_hora');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movimentacoes_estoque');
    }
};
