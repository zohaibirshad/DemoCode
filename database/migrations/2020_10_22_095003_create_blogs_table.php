<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blogs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('storename', 255)->nullable();
            $table->bigInteger('category_id');
            $table->foreign('category_id')->references('id')->on('categories');
            $table->string('title', 255);
            $table->text('details');
            $table->string('photo', 255)->nullable();
            $table->string('source', 255);
            $table->bigInteger('views', 255)->default(0);
            $table->tinyInteger('status', 255)->default(1);
            $table->text('meta_tag')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('tags')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blogs');
    }
}
