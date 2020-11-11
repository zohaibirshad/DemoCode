<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSocialsettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('socialsettings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('storename', 255)->nullable();
            $table->string('facebook', 255);
            $table->string('gplus', 255);
            $table->string('twitter', 255);
            $table->string('linkedin', 255);
            $table->string('dribble', 255)->nullable();
            $table->tinyInteger('f_status')->default(1);
            $table->tinyInteger('g_status')->default(1);
            $table->tinyInteger('t_status')->default(1);
            $table->tinyInteger('l_status')->default(1);
            $table->tinyInteger('d_status')->default(1);
            $table->tinyInteger('f_check')->nullable();
            $table->tinyInteger('g_check')->nullable();
            $table->text('fclient_id')->nullable();
            $table->text('fclient_secret')->nullable();
            $table->text('fredirect')->nullable();
            $table->text('gclient_id')->nullable();
            $table->text('gclient_secret')->nullable();
            $table->text('gredirect')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('socialsettings');
    }
}
