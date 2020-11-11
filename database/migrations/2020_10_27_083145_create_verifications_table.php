<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVerificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('verifications', function (Blueprint $table) {
            $table->increments('id');
            $table->string('storename', 255)->nullable();
            $table->integer('user_id')->default(0);
            $table->foreign('user_id')->references('id')->on('users');
            $table->text('attachments')->nullable();
            $table->enum('status',['Pending', 'Verified', 'Declined'])->nullable();
            $table->text('text')->nullable();
            $table->tinyInteger('admin_warning')->default(0);
            $table->text('warning_reason')->nullable();
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
        Schema::dropIfExists('verifications');
    }
}
