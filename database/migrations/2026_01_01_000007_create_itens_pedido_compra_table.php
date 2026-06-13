<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('itens_pedido_compra', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_compra_id')->constrained('pedidos_compra')->cascadeOnDelete();
            $table->foreignId('produto_id')->constrained('produtos')->restrictOnDelete();
            $table->decimal('quantidade_solicitada', 12, 3);
            $table->decimal('quantidade_recebida', 12, 3)->default(0);
            $table->decimal('custo_unitario_previsto', 14, 4);
            $table->decimal('custo_unitario_real', 14, 4)->nullable()
                ->comment('Preenchido na NF de entrada');
            $table->timestamps();

            $table->index('pedido_compra_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('itens_pedido_compra');
    }
};
