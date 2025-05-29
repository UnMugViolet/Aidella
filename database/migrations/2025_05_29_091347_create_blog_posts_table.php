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
        Schema::create('blog_posts', function (Blueprint $table) {
            $table->id();
            $table->string('title')->unique();
            $table->text('content');

            
            $table->string('status')->default('draft');
            $table->string('meta_title')->unique();
            $table->string('meta_description');
            $table->string('slug')->unique();
            
            $table->foreignId('post_category_id')
                ->constrained('post_categories')
                ->onDelete('restrict');
            $table->foreignId('dog_race_id')
                ->nullable()
                ->constrained('dog_races')
                ->nullOnDelete();
            $table->foreignId('author_id')
                ->nullable()
                ->default(1)
                ->constrained('users')
                ->onDelete('set default');

            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_posts');
    }
};
