<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('storename', 255)->nullable();
            $table->string('title', 255);
            $table->string('slug', 255);
            $table->text('details');
            $table->text('meta_tag')->nullable();
            $table->text('meta_description')->nullable();
            $table->tinyInteger('header')->default(0);
            $table->tinyInteger('footer')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pages');
    }
}
