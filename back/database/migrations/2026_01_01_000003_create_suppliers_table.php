<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('tax_id', 18)->unique()->comment('Format: XX.XXX.XXX/XXXX-XX');
            $table->string('legal_name');
            $table->unsignedSmallInteger('delivery_lead_time_days')->default(7);
            $table->string('default_payment_terms')->nullable()
                ->comment('E.g.: 30/60/90, CASH');
            $table->string('contact_email')->nullable();
            $table->string('phone', 20)->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
