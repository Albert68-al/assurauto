<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('comesa_country_configs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pays_id')->unique();
            $table->decimal('taux', 10, 4)->default(0);
            $table->decimal('tva', 5, 2)->default(0);
            $table->text('legislation')->nullable();
            $table->timestamps();

            // foreign key if pays table exists
            if (Schema::hasTable('pays')) {
                $table->foreign('pays_id')->references('id')->on('pays')->onDelete('cascade');
            }
        });
    }

    public function down()
    {
        Schema::dropIfExists('comesa_country_configs');
    }
};
