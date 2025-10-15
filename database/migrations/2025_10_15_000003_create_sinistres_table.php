<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sinistres', function (Blueprint $table) {
            $table->id();
            $table->foreignId('police_id')->constrained()->onDelete('cascade');
            $table->string('numero_sinistre')->unique();
            $table->date('date_sinistre');
            $table->string('lieu');
            $table->text('description');
            $table->string('statut')->default('déclaré');
            $table->decimal('montant_dommages', 12, 2)->nullable();
            $table->text('circonstances')->nullable();
            $table->string('tiers_implique')->nullable();
            $table->string('tiers_assureur')->nullable();
            $table->string('tiers_immatriculation')->nullable();
            $table->text('temoignages')->nullable();
            $table->text('constat_amiable')->nullable();
            $table->text('expertise')->nullable();
            $table->date('date_expertise')->nullable();
            $table->decimal('montant_indemnisation', 12, 2)->nullable();
            $table->date('date_indemnisation')->nullable();
            $table->text('commentaires')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sinistres');
    }
};
