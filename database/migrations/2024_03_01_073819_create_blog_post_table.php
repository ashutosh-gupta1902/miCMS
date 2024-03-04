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
        Schema::create('blog_post', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->nullOnDelete();
            $table->string('title');
            $table->string('thumbnail')->nullable();
            $table->string('color');
            $table->string('slug')->unique();
            $table->longText('content')->nullable();
            $table->json('tags')->nullable();
            $table->enum('status', ['Publish', 'Future', 'Draft', 'AutoDraft', 'Pending', 'Private', 'Trash', 'Inherit'])
                ->default('Publish');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_post');
    }
};
