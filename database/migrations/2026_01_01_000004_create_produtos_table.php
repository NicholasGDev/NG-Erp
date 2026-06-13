<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('produtos', function (Blueprint $table) {
            $table->id();
            $table->string('sku')->unique()->comment('Stock Keeping Unit');
            $table->string('nome');
            $table->string('unidade_medida', 5)->default('UN')
                ->comment('UN | KG | CX | LT | MT');
            $table->decimal('estoque_minimo', 12, 3)->default(0);
            $table->decimal('estoque_maximo', 12, 3)->nullable();
            $table->string('metodo_custo')->default('CUSTO_MEDIO')
                ->comment('PEPS | CUSTO_MEDIO');
            $table->decimal('custo_medio_atual', 14, 4)->default(0)
                ->comment('Atualizado a cada movimentacao de entrada');
            $table->boolean('controla_lote')->default(false);
            $table->boolean('ativo')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index('sku');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produtos');
    }
};
