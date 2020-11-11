<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRatingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ratings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('storename', 255)->nullable();
            $table->integer('user_id')->default(0);
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('product_id')->default(0);
            $table->foreign('product_id')->references('id')->on('products');
            $table->text('review')->nullable();
            $table->tinyInteger('rating');
            $table->dateTime('review_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ratings');
    }
}
