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
        Schema::create('import_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('import_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('file_key');
            $table->string('file_name');
            $table->string('import_type');
            $table->integer('row_number');
            $table->string('error_column_value')->nullable();
            $table->string('error_column');
            $table->text('error_message');
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('import_logs');
    }
};
