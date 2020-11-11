<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_subscriptions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('storename', 255)->nullable();
            $table->integer('user_id')->default(0);
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('subscription_id')->default(0);
            $table->foreign('subscription_id')->references('id')->on('subscriptions');
            $table->text('title');
            $table->string('currency', 255);
            $table->string('currency_code', 255);
            $table->double('price')->default(0);
            $table->integer('days');
            $table->integer('allowed_products')->default(0);
            $table->text('details')->nullable();
            $table->string('method', 255)->default('Free');
            $table->string('txnid', 255)->nullable();
            $table->string('charge_id', 255)->nullable();
            $table->timestamps();
            $table->integer('status')->default(0);
            $table->text('payment_number')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_subscriptions');
    }
}
