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
            $table->id();
            $table->string("name");
            $table->string("price");
            $table->string("quantity");
            $table->string("slug");
            $table->integer("status");
            $table->string("avatar");
            $table->string("evaluate");
            $table->string("category_id");
            $table->string("size");
            $table->string("color");
            $table->string("brand");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
