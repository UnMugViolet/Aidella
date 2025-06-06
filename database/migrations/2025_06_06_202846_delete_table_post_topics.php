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
        Schema::dropIfExists('blog_posts_post_topics');
        Schema::dropIfExists('post_topics');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('post_topics', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('blog_posts_post_topics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('blog_post_id')->constrained()->onDelete('cascade');
            $table->foreignId('post_topic_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }
};
