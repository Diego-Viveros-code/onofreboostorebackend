<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            // Primary Key
            $table->bigIncrements('book_id');
            $table->string('title', 255);
            $table->string('description', 255);
            $table->integer('price');
            $table->string('cover', 255);
            $table->string('author', 255)->nullable();

            // Timestamps
            $table->timestampTz('created_at')->nullable();
            $table->timestampTz('updated_at')->nullable();

            // Soft deletes
            $table->timestampTz('deleted_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
