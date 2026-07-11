<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->restrictOnDelete();
            $table->string('batch_number');
            $table->date('manufactured_at')->nullable();
            $table->date('expires_at')->nullable()
                ->comment('Null = product has no expiration');
            $table->string('status')->default('available')
                ->comment('available | quarantine | expired | depleted');
            $table->decimal('available_quantity', 12, 3)->default(0);
            $table->timestamps();

            $table->unique(['product_id', 'batch_number']);
            $table->index(['product_id', 'status']);
            $table->index('expires_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lotes');
    }
};
