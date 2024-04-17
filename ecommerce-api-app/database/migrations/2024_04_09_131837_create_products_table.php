<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            // $table->bigInteger('category_id')->unsigned();
            // $table->bigInteger('brand_id')->unsigned();
            $table->string('name'); 
            $table->boolean('is_trendy')->default(false); 
            $table->boolean('is_available')->default(true);
            $table->double('price',8,2);
            $table->integer('amount');
            $table->double('discount',8,2)->nullable();
            $table->string('image');
            $table->timestamps();

            $table->foreignId('brand_id')->references('id')->on('brands')->onDelete('cascade');
            $table->foreignId('category_id')->references('id')->on('categorys')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productss');
    }
};
