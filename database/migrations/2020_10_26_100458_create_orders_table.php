<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('storename', 255)->nullable();
            $table->integer('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->text('cart');
            $table->string('method', 255)->nullable();
            $table->string('shipping', 255)->nullable();
            $table->string('pickup_location', 255)->nullable();
            $table->string('totalQty', 255);
            $table->float('pay_amount',  8, 2);
            $table->string('txnid', 255)->nullable();
            $table->string('charge_id', 255)->nullable();
            $table->string('order_number', 255);
            $table->string('payment_status', 255);
            $table->string('customer_email', 255);
            $table->string('customer_name', 255);
            $table->string('customer_country', 255);
            $table->string('customer_phone', 255);
            $table->string('customer_address', 255)->nullable();
            $table->string('customer_city', 255)->nullable();
            $table->string('customer_zip', 255)->nullable();
            $table->string('shipping_name', 255)->nullable();
            $table->string('shipping_country', 255)->nullable();
            $table->string('shipping_email', 255)->nullable();
            $table->string('shipping_phone', 255)->nullable();
            $table->string('shipping_address', 255)->nullable();
            $table->string('shipping_city', 255)->nullable();
            $table->string('shipping_zip', 255)->nullable();
            $table->text('order_note')->nullable();
            $table->string('coupon_code', 255)->nullable();
            $table->string('coupon_discount', 255)->nullable();
            $table->enum('status', ['pending','processing','completed','declined','on delivery'])->default('pending');
            $table->string('affilate_user', 255)->nullable();
            $table->string('affilate_charge', 255)->nullable();
            $table->string('currency_sign', 255);
            $table->double('currency_value', 8, 2);
            $table->double('shipping_cost', 8, 2);
            $table->double('packing_cost', 8, 2)->default(0);
            $table->integer('tax');
            $table->tinyInteger('dp')->default(0);
            $table->text('pay_id')->nullable();
            $table->integer('vendor_shipping_id')->default(0);
            $table->integer('vendor_packing_id')->default(0);
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
        Schema::dropIfExists('orders');
    }
}
