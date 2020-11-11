<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->increments('id');
            $table->string('storename', 255)->nullable();
            $table->string('name', 191);
            $table->string('email', 191);
            $table->string('phone', 191);
            $table->integer('role_id')->default(0);
            $table->foreign('role_id')->references('id')->on('roles');
            $table->string('photo', 191)->nullable();
            $table->string('password', 191);
            $table->tinyInteger('status')->default(1);
            $table->rememberToken()->nullable();
            $table->timestamps();
            $table->text('shop_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admins');
    }
}
