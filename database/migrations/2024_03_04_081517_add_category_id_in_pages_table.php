<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCategoryIdInPagesTable extends Migration
{
    public function up()
    {
        Schema::table('pages', function (Blueprint $table) {
            // Add the category_id column
            $table->unsignedBigInteger('page_category_id');

            // Define foreign key constraint
            $table->foreign('page_category_id')->references('id')->on('blog_categories')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('pages', function (Blueprint $table) {
            // Drop the foreign key constraint
            $table->dropForeign(['category_id']);

            // Drop the category_id column
            $table->dropColumn('category_id');
        });
    }
}
