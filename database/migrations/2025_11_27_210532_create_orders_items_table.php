<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            // Primary key
            $table->integer('order_items_id')->primary();

            // Foreign-like columns
            $table->integer('order_id')->nullable(false);
            $table->integer('book_id')->nullable(false);

            // Quantity with default
            $table->integer('quantity');

            // Price
            $table->integer('price');

            // timestamps with default now()
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
