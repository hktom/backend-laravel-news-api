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
        Schema::create('articles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('url');
            $table->string('title');
            $table->string('publishedAt');
            $table->text('description')->nullable();
            $table->text('content')->nullable();
            $table->string('image')->nullable();
            $table->string('category_id')->nullable();
            $table->string('category_name')->nullable();
            $table->string('source_name')->nullable();
            $table->string('source_id')->nullable();
            $table->string('author_id')->nullable();
            $table->string('author_name')->nullable();
            $table->tinyInteger('read_later')->default(0);
            $table->tinyInteger('favorites')->default(0);
            $table->tinyInteger('already_read')->default(0);
            $table->uuid('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }
    // 085 37 50 850

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
