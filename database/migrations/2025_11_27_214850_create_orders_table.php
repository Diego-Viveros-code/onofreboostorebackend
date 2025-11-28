<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {

            // Primary key
            $table->integer('order_id')->primary();

            // Foreign user
            $table->integer('user_id')->nullable(false);

            // Total
            $table->integer('total')->nullable(false);

            // Status
            $table->string('status', 50);

            // UUID with default gen_random_uuid()
            $table->uuid('transaction_id')->default(DB::raw('gen_random_uuid()'));

            // created_at y updated_at con DEFAULT now()
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();

            // Soft delete
            $table->timestamp('deleted_at')->nullable();

            // Foreign key opcional
            $table->foreign('user_id')
                  ->references('id')->on('users')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
