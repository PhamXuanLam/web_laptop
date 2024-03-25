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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string("username", 100)->uniqid();
            $table->string("password");
            $table->string("role", 10);
            $table->integer("status");
            $table->string('first_name', 20)->nullable();
            $table->string('last_name', 20)->nullable();
            $table->date('birth_day')->nullable();
            $table->string('email', 100)->uniqid();
            $table->string('phone', 20)->uniqid();
            $table->string('avatar')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
