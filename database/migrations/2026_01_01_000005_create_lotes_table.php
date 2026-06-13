<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lotes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produto_id')->constrained('produtos')->restrictOnDelete();
            $table->string('numero_lote');
            $table->date('data_fabricacao')->nullable();
            $table->date('data_validade')->nullable()
                ->comment('Null = produto sem validade');
            $table->string('status')->default('disponivel')
                ->comment('disponivel | quarentena | vencido | esgotado');
            $table->decimal('quantidade_disponivel', 12, 3)->default(0);
            $table->timestamps();

            $table->unique(['produto_id', 'numero_lote']);
            $table->index(['produto_id', 'status']);
            $table->index('data_validade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lotes');
    }
};
