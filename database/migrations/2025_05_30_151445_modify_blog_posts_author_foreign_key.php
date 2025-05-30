<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('blog_posts', function (Blueprint $table) {
            $table->dropForeign(['author_id']);
            
            // Add foreign key with SET NULL on delete
            $table->foreign('author_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('blog_posts', function (Blueprint $table) {
            $table->dropForeign(['author_id']);
            
            // Restore original constraint (adjust as needed)
            $table->foreign('author_id')
                  ->references('id')
                  ->on('users');
        });
    }
};
