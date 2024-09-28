<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('supplier_id');
            $table->enum('payment_method', ['cash', 'bank_transfer'])->default('cash');
            $table->enum('status', ['paid', 'on_hold', 'rejected', 'draft', 'cancelled', 'refunded', 'created']);
            $table->decimal('discount_amount')->default(0);
            $table->string('discount_currency');
            $table->decimal('grand_total')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
