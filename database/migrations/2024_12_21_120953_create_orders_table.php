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
            $table->date('date')->nullable();
            $table->string('channel')->nullable();
            $table->string('sku')->nullable();
            $table->text('item_description')->nullable();
            $table->string('origin')->nullable();
            $table->string('so_num')->nullable();
            $table->double('cost')->nullable();
            $table->double('shipping_cost')->nullable();
            $table->double('total_price')->nullable();
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
