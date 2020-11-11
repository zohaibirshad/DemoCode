<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('storename', 255)->nullable();
            $table->string('name', 255);
            $table->string('photo', 255)->nullable();
            $table->string('zip', 255)->nullable();
            $table->string('city', 255)->nullable();
            $table->string('country', 255)->nullable();
            $table->string('address', 255)->nullable();
            $table->string('phone', 255)->nullable();
            $table->string('fax', 255)->nullable();
            $table->string('email', 255);
            $table->string('password', 255)->nullable();
            $table->string('remember_token', 255)->nullable();
            $table->timestamps();
            $table->tinyInteger('is_provider')->default(0);
            $table->tinyInteger('status')->default(0);
            $table->text('verification_link')->nullable();
            $table->enum('email_verified',['Yes', 'No'])->nullable();
            $table->text('affilate_code')->nullable();
            $table->double('affilate_income')->default(0);
            $table->text('shop_name')->nullable();
            $table->text('owner_name')->nullable();
            $table->text('shop_number')->nullable();
            $table->text('shop_address')->nullable();
            $table->text('reg_number')->nullable();
            $table->text('shop_message')->nullable();
            $table->text('shop_details')->nullable();
            $table->string('shop_image', 255)->nullable();
            $table->text('f_url')->nullable();
            $table->text('g_url')->nullable();
            $table->text('t_url')->nullable();
            $table->text('l_url')->nullable();
            $table->tinyInteger('is_vendor')->default(0);
            $table->tinyInteger('f_check')->default(0);
            $table->tinyInteger('g_check')->default(0);
            $table->tinyInteger('t_check')->default(0);
            $table->tinyInteger('l_check')->default(0);
            $table->tinyInteger('mail_sent')->default(0);
            $table->double('shipping_cost')->default(0);
            $table->double('current_balance')->default(0);
            $table->date('date')->nullable();
            $table->tinyInteger('ban')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
