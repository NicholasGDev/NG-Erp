<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('physical_inventory_counts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('physical_inventory_id')->constrained('physical_inventories')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->restrictOnDelete();
            $table->foreignId('batch_id')->nullable()->constrained('batches')->nullOnDelete();
            $table->decimal('system_quantity', 12, 3)
                ->comment('System balance at the time of the inventory');
            $table->decimal('physical_quantity', 12, 3)->nullable()
                ->comment('Actual count by the operator');
            $table->decimal('variance', 12, 3)->nullable()
                ->comment('physical_quantity - system_quantity');
            $table->boolean('adjustment_applied')->default(false);
            $table->timestamps();

            $table->index(['physical_inventory_id', 'product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contagens_inventario');
    }
};
