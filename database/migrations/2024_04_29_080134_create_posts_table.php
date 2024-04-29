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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string("thumbnail")->nullable();
            $table->string("color");
            $table->string("title");
            $table->string("slug")->unique();
            $table->unsignedBigInteger("category_id"); // Assuming the id column in categories table is unsignedBigInteger
            $table->foreign("category_id")->references("id")->on("categories")->onDelete("cascade");
            $table->text("content")->nullable();
            $table->json("tags")->nullable();
            $table->boolean("published")->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
