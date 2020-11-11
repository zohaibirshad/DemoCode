<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminUserConversationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_user_conversations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('storename', 200)->nullable();
            $table->string('subject', 191);
            $table->bigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('message', 191);
            $table->timestamps();
            $table->enum('type', ['Ticket', 'Dispute'])->nullable();
            $table->text('order_number')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_user_conversations');
    }
}
