<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('email')->unique();
        $table->timestamp('email_verified_at')->nullable();
        $table->string('password');
        $table->string('nationality')->nullable();
        $table->string('id_number')->nullable();
        $table->string('phone_number')->nullable();
        $table->string('address')->nullable();
        $table->string('city')->nullable();
        $table->string('postal_code')->nullable();
        $table->rememberToken();
        $table->timestamps();
    });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};