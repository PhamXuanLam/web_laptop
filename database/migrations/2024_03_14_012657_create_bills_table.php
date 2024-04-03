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
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger("supplier_id");
            $table->unsignedInteger("admin_id");
            $table->unsignedInteger("product_id");
            $table->integer("quantity");
            $table->double("discount");
            $table->double("total");
            $table->integer("tax");
            $table->double("pay");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bills');
    }
};
