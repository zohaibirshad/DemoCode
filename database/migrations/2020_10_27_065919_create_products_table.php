<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('storename', 255)->nullable();
            $table->string('sku', 255)->nullable();
            $table->enum('product_type', ['normal', 'affiliate']);
            $table->text('affiliate_link')->nullable();
            $table->integer('user_id')->default(0);
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('category_id')->default(0);
            $table->foreign('category_id')->references('id')->on('categories');
            $table->integer('subcategory_id')->nullable();
            $table->foreign('subcategory_id')->references('id')->on('subcategories');
            $table->integer('childcategory_id')->nullable();
            $table->foreign('childcategory_id')->references('id')->on('childcategories');
            $table->text('attributes')->nullable();
            $table->text('name');
            $table->text('slug')->nullable();
            $table->text('photo');
            $table->string('thumbnail', 255)->nullable();
            $table->string('file', 255)->nullable();
            $table->string('size', 255)->nullable();
            $table->string('size_qty', 255)->nullable();
            $table->string('size_price', 255)->nullable();
            $table->text('color')->nullable();
            $table->double('price',8,2);
            $table->double('previous_price',8,2)->nullable();
            $table->text('details')->nullable();
            $table->integer('stock')->nullable();
            $table->text('policy')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->integer('views')->default(0);
            $table->string('tags', 255)->nullable();
            $table->text('features')->nullable();
            $table->text('colors')->nullable();
            $table->tinyInteger('product_condition')->default(0);
            $table->string('ship', 255)->nullable();
            $table->tinyInteger('is_meta')->default(0);
            $table->text('meta_tag')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('youtube', 255)->nullable();
            $table->enum('type', ['Physical', 'Digital', 'License'])->nullable();
            $table->text('license')->nullable();
            $table->text('license_qty')->nullable();
            $table->text('link')->nullable();
            $table->string('platform', 255)->nullable();
            $table->string('region', 255)->nullable();
            $table->string('licence_type', 255)->nullable();
            $table->string('measure', 255)->nullable();
            $table->tinyInteger('featured')->default(0);
            $table->tinyInteger('best')->default(0);
            $table->tinyInteger('top')->default(0);
            $table->tinyInteger('hot')->default(0);
            $table->tinyInteger('latest')->default(0);
            $table->tinyInteger('big')->default(0);
            $table->tinyInteger('trending')->default(0);
            $table->tinyInteger('sale')->default(0);
            $table->timestamps();
            $table->tinyInteger('is_discount')->default(0);
            $table->text('discount_date')->nullable();
            $table->text('whole_sell_qty')->nullable();
            $table->text('whole_sell_discount')->nullable();
            $table->tinyInteger('is_catalog')->default(0);
            $table->integer('catalog_id')->default(0);
            $table->integer('ali_express')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
