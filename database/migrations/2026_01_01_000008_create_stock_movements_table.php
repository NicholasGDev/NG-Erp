<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->restrictOnDelete();
            $table->foreignId('batch_id')->nullable()->constrained('batches')->nullOnDelete();
            $table->foreignId('source_warehouse_id')->nullable()->constrained('warehouses')->nullOnDelete();
            $table->foreignId('destination_warehouse_id')->nullable()->constrained('warehouses')->nullOnDelete();
            $table->string('movement_type')
                ->comment('purchase_in | sale_out | transfer | loss_adjustment | gain_adjustment | return');
            $table->decimal('quantity', 12, 3)
                ->comment('Uses decimal(12,3) to support KG, liters, etc.');
            $table->decimal('movement_unit_cost', 14, 4)->default(0);
            $table->decimal('balance_after_movement', 12, 3)
                ->comment('Kardex: balance computed after this movement');
            $table->dateTime('occurred_at');
            $table->unsignedBigInteger('user_id')->comment('FK to the system users table');
            $table->string('reference_document')->nullable()
                ->comment('Invoice, sales_order_id, transfer_order_id, etc.');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['product_id', 'occurred_at']);
            $table->index(['product_id', 'destination_warehouse_id']);
            $table->index('movement_type');
            $table->index('data_hora');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movimentacoes_estoque');
    }
};
