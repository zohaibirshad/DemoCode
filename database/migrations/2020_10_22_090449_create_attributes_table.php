<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttributesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attributes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('storename', 255)->nullable();
            $table->bigInteger('attributable_id')->nullable();
            $table->foreign('attributable_id')->references('id')->on('attributes');
            $table->string('attributable_type', 255)->nullable();
            $table->string('name', 255)->nullable();
            $table->string('input_name', 255)->nullable();
            $table->bigInteger('price_status')->default(1);
            $table->bigInteger('details_status')->default(1);
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
        Schema::dropIfExists('attributes');
    }
}
