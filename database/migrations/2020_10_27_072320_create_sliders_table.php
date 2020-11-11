<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSlidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sliders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('storename', 255)->nullable();
            $table->text('subtitle_text')->nullable();
            $table->string('subtitle_size', 255)->nullable();
            $table->string('subtitle_color', 255)->nullable();
            $table->string('subtitle_anime', 255)->nullable();
            $table->text('title_text')->nullable();
            $table->string('title_size', 255)->nullable();
            $table->string('title_color', 255)->nullable();
            $table->string('title_anime', 255)->nullable();
            $table->text('details_text')->nullable();
            $table->string('details_size', 255)->nullable();
            $table->string('details_color', 255)->nullable();
            $table->string('details_anime', 255)->nullable();
            $table->string('photo', 255)->nullable();
            $table->string('position', 255)->nullable();
            $table->text('link')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sliders');
    }
}
