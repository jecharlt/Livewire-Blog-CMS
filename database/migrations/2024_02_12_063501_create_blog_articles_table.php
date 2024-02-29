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
        Schema::create('blog_articles', function (Blueprint $table) {
            $table->id();
            $table->string('title')->unique();
            $table->string('description');
            $table->string('slug')->unique();
            $table->foreignId('category_id')->constrained('blog_categories')->cascadeOnDelete();
            $table->foreignId('article_type_id')->constrained('blog_article_types')->cascadeOnDelete();
            $table->text('featured_image')->nullable();
            $table->longText('content');
            $table->boolean('is_published');
            $table->timestamp('originally_published_at')->nullable();
            $table->timestamp('republished_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_articles');
    }
};
