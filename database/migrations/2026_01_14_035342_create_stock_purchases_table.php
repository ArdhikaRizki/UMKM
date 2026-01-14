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
        Schema::create('stock_purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->integer('quantity'); // Jumlah stok yang dibeli
            $table->integer('purchase_price'); // Harga beli per unit
            $table->integer('total_cost'); // Total biaya (quantity * purchase_price)
            $table->foreignId('user_id')->constrained(); // Siapa yang input
            $table->text('notes')->nullable(); // Catatan tambahan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_purchases');
    }
};
