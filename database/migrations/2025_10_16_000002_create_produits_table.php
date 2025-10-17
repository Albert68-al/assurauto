<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProduitsTable extends Migration
{
    public function up()
    {
        Schema::create('produits', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->text('description')->nullable();
            $table->text('couverture');
            $table->decimal('tarif_base', 12, 2);
            $table->decimal('taux', 5, 2)->default(0); // Taux spécifique au produit
            $table->decimal('tva', 5, 2)->default(0);  // TVA spécifique au produit
            $table->char('devise', 3)->default('USD');
            $table->foreignId('pays_id')->constrained('pays')->onDelete('cascade');
            $table->boolean('actif')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        // Ajout des index
        Schema::table('produits', function (Blueprint $table) {
            $table->index('pays_id');
            $table->index('actif');
        });
    }

    public function down()
    {
        Schema::dropIfExists('produits');
    }
}
