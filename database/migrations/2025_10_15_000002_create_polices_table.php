<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('polices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->string('numero_police')->unique();
            $table->date('date_effet');
            $table->date('date_echeance');
            $table->decimal('montant_prime', 12, 2);
            $table->string('statut')->default('en_attente');
            $table->string('type_vehicule');
            $table->string('immatriculation');
            $table->string('marque');
            $table->string('modele');
            $table->integer('annee');
            $table->string('type_couverture');
            $table->text('garanties')->nullable();
            $table->text('franchises')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('polices');
    }
};
