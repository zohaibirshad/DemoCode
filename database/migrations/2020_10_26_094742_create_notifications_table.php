<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->increments('id');
            $table->string('storename', 255)->nullable();
            $table->integer('order_id')->nullable();
            $table->foreign('order_id')->references('id')->on('orders');
            $table->integer('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('vendor_id')->nullable();
            $table->bigInteger('product_id')->nullable();
            $table->foreign('product_id')->references('id')->on('products');
            $table->bigInteger('conversation_id')->nullable();
            $table->foreign('conversation_id')->references('id')->on('conversations');
            $table->tinyInteger('is_read')->default(0);
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
        Schema::dropIfExists('notifications');
    }
}
