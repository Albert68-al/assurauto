<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('produits') && !Schema::hasColumn('produits', 'duree')) {
            Schema::table('produits', function (Blueprint $table) {
                $table->string('duree')->nullable()->after('tarif_base');
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('produits') && Schema::hasColumn('produits', 'duree')) {
            Schema::table('produits', function (Blueprint $table) {
                $table->dropColumn('duree');
            });
        }
    }
};
