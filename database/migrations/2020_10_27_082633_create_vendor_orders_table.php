<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVendorOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->default(0);
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('order_id')->default(0);
            $table->foreign('order_id')->references('id')->on('orders');
            $table->integer('qty');
            $table->double('price');
            $table->string('order_number', 255);
            $table->enum('status', ['pending','processing','completed','declined','on delivery'])->default('pending');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vendor_orders');
    }
}
