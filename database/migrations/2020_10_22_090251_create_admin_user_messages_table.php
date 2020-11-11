<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminUserMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_user_messages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('storename', 255)->nullable();
            $table->bigInteger('conversation_id');
            $table->foreign('conversation_id')->references('id')->on('conversations');
            $table->text('message');
            $table->bigInteger('user_id')->nullable();
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
        Schema::dropIfExists('admin_user_messages');
    }
}
