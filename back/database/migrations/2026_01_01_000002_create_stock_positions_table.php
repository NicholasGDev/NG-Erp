<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_positions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warehouse_id')->constrained('warehouses')->cascadeOnDelete();
            $table->string('aisle', 10)->nullable()->comment('E.g.: A, B, C');
            $table->string('shelf', 10)->nullable()->comment('E.g.: 01, 02');
            $table->string('level', 10)->nullable()->comment('E.g.: A, B (top, bottom)');
            $table->string('status')->default('free')
                ->comment('free | occupied | blocked');
            $table->timestamps();

            $table->unique(['warehouse_id', 'aisle', 'shelf', 'level'], 'stock_position_unique');
            $table->index(['warehouse_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_positions');
    }
};
