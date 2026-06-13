<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pedidos_compra', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fornecedor_id')->constrained('fornecedores')->restrictOnDelete();
            $table->string('numero_pedido')->unique()->nullable()
                ->comment('Numeracao interna do ERP');
            $table->dateTime('data_emissao');
            $table->date('data_prevista_entrega')->nullable();
            $table->string('status')->default('rascunho')
                ->comment('rascunho | emitido | recebido_parcial | concluido | cancelado');
            $table->decimal('valor_total', 14, 2)->default(0);
            $table->text('observacoes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['fornecedor_id', 'status']);
            $table->index('data_emissao');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pedidos_compra');
    }
};
