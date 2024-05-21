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
        Schema::create('description', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger("product_id");
            $table->string("guarantee")->nullable(); // Bảo hành
            $table->float("mass")->nullable(); // khối lượng
            $table->string("cpu")->nullable();
            $table->string("screen")->nullable();
            $table->string("storage")->nullable(); // lưu trữ
            $table->string("graphics")->nullable(); //đồ họa,
            $table->string("battery")->nullable(); // pin,
            $table->string("operating_system")->nullable(); //hệ điều hành
            $table->string("ram")->nullable();
            $table->string("other")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('description');
    }
};
