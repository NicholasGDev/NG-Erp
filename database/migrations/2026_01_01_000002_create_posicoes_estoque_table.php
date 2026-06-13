<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posicoes_estoque', function (Blueprint $table) {
            $table->id();
            $table->foreignId('armazem_id')->constrained('armazens')->cascadeOnDelete();
            $table->string('corredor', 10)->nullable()->comment('Ex: A, B, C');
            $table->string('prateleira', 10)->nullable()->comment('Ex: 01, 02');
            $table->string('nivel', 10)->nullable()->comment('Ex: A, B (alto, baixo)');
            $table->string('status')->default('livre')
                ->comment('livre | ocupado | bloqueado');
            $table->timestamps();

            $table->unique(['armazem_id', 'corredor', 'prateleira', 'nivel'], 'posicao_unique');
            $table->index(['armazem_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posicoes_estoque');
    }
};
