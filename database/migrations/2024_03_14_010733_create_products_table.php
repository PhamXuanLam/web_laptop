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
            $table->double("price");
            $table->integer("quantity");
            $table->string("slug")->nullable();
            $table->integer("status")->nullable()->default(1);
            $table->string("avatar")->nullable();
            $table->integer("evaluate")->nullable()->default(0);
            $table->unsignedInteger("category_id");
            $table->string("size");
            $table->string("color");
            $table->string("demand");
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
