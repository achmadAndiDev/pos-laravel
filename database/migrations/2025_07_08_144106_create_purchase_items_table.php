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
        Schema::create('purchase_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_id')->constrained('purchases')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->integer('quantity');
            $table->decimal('unit_price', 15, 2); // Harga beli per unit
            $table->decimal('total_price', 15, 2); // quantity * unit_price
            $table->text('notes')->nullable();
            $table->timestamps();

            // Indexes
            $table->index(['purchase_id', 'product_id']);
            $table->unique(['purchase_id', 'product_id']); // Prevent duplicate product in same purchase
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_items');
    }
};
