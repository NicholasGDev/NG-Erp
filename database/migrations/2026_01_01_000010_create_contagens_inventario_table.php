<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contagens_inventario', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventario_id')->constrained('inventarios')->cascadeOnDelete();
            $table->foreignId('produto_id')->constrained('produtos')->restrictOnDelete();
            $table->foreignId('lote_id')->nullable()->constrained('lotes')->nullOnDelete();
            $table->decimal('quantidade_sistema', 12, 3)
                ->comment('Saldo do sistema no momento do inventario');
            $table->decimal('quantidade_fisica', 12, 3)->nullable()
                ->comment('Contagem real do operador');
            $table->decimal('divergencia', 12, 3)->nullable()
                ->comment('quantidade_fisica - quantidade_sistema');
            $table->boolean('ajuste_aplicado')->default(false);
            $table->timestamps();

            $table->index(['inventario_id', 'produto_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contagens_inventario');
    }
};
