<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fornecedores', function (Blueprint $table) {
            $table->id();
            $table->string('cnpj', 18)->unique()->comment('Formato: XX.XXX.XXX/XXXX-XX');
            $table->string('razao_social');
            $table->unsignedSmallInteger('prazo_entrega_dias')->default(7);
            $table->string('condicao_pagamento_padrao')->nullable()
                ->comment('Ex: 30/60/90, A_VISTA');
            $table->string('email_contato')->nullable();
            $table->string('telefone', 20)->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fornecedores');
    }
};
