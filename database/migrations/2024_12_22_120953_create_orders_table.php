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
            $table->foreignId('import_id')->nullable()->constrained()->onDelete('set null');
            $table->date('order_date');
            $table->string('channel');
            $table->string('sku');
            $table->text('item_description')->nullable();
            $table->string('origin');
            $table->string('so_num');
            $table->decimal('cost', 10, 2);
            $table->decimal('shipping_cost', 10, 2);
            $table->decimal('total_price', 10, 2);
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
