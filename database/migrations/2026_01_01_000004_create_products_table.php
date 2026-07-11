<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('sku')->unique()->comment('Stock Keeping Unit');
            $table->string('name');
            $table->string('unit_of_measure', 5)->default('EA')
                ->comment('EA | KG | BX | L | M');
            $table->decimal('minimum_stock', 12, 3)->default(0);
            $table->decimal('maximum_stock', 12, 3)->nullable();
            $table->string('costing_method')->default('AVERAGE_COST')
                ->comment('FIFO | AVERAGE_COST');
            $table->decimal('current_average_cost', 14, 4)->default(0)
                ->comment('Updated on every inbound movement');
            $table->boolean('tracks_batch')->default(false);
            $table->boolean('active')->default(true);
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
