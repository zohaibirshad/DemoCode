<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWithdrawsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('withdraws', function (Blueprint $table) {
            $table->increments('id');
            $table->string('storename', 255)->nullable();
            $table->integer('user_id')->default(0);
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('method', 255)->nullable();
            $table->string('acc_email', 255)->nullable();
            $table->string('iban', 255)->nullable();
            $table->string('country', 255)->nullable();
            $table->string('acc_name', 255)->nullable();
            $table->text('address')->nullable();
            $table->string('swift', 255)->nullable();
            $table->text('reference')->nullable();
            $table->float('amount')->nullable();
            $table->float('fee')->default(0);
            $table->timestamps();
            $table->enum('status',['pending', 'completed', 'rejected'])->default('pending');
            $table->enum('fee',['user', 'vendor']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('withdraws');
    }
}
