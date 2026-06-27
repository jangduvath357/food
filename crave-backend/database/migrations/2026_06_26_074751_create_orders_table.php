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
        $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
        $table->enum('status', ['pending', 'preparing', 'delivered', 'cancelled'])->default('pending');
        $table->enum('order_type', ['delivery', 'pickup'])->default('delivery');
        $table->decimal('subtotal', 8, 2);
        $table->decimal('delivery_fee', 8, 2)->default(0);
        $table->decimal('discount', 8, 2)->default(0);
        $table->decimal('total', 8, 2);
        $table->string('promo_code')->nullable();
        $table->string('delivery_address')->nullable();
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
