<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaysTable extends Migration
{
    public function up()
    {
        Schema::create('pays', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 100);
            $table->char('code', 3)->unique(); // Code ISO 3166-1 alpha-3
            $table->char('devise', 3)->default('USD'); // Code devise ISO 4217
            $table->decimal('tva_par_defaut', 5, 2)->default(0);
            $table->boolean('actif')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        // Ajout des index
        Schema::table('pays', function (Blueprint $table) {
            $table->index('actif');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pays');
    }
}
