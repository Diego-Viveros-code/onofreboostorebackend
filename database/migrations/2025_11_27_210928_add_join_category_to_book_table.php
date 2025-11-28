<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('books', function (Blueprint $table) {

            if (!Schema::hasColumn('books', 'category_id')) {
                $table->unsignedBigInteger('category_id')->nullable();
            }

            $table->foreign('category_id')
                  ->references('category_id') 
                  ->on('category')             
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
        });
    }
};
