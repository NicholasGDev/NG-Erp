<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('armazem_id')->constrained('armazens')->restrictOnDelete();
            $table->dateTime('data_inicio');
            $table->dateTime('data_fim')->nullable();
            $table->string('status')->default('em_andamento')
                ->comment('em_andamento | ajustado | cancelado');
            $table->unsignedBigInteger('usuario_responsavel_id');
            $table->text('observacoes')->nullable();
            $table->timestamps();

            $table->index(['armazem_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventarios');
    }
};
