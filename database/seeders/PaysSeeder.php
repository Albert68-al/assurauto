<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pays;

class PaysSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $countries = [
            ['nom' => 'Kenya', 'code' => 'KE', 'devise' => 'KES', 'tva_par_defaut' => 16, 'actif' => true],
            ['nom' => 'Rwanda', 'code' => 'RW', 'devise' => 'RWF', 'tva_par_defaut' => 18, 'actif' => true],
            ['nom' => 'rdc', 'code' => 'CD', 'devise' => 'CDF', 'tva_par_defaut' => 16, 'actif' => true],
            ['nom' => 'Burundi', 'code' => 'BI', 'devise' => 'BIF', 'tva_par_defaut' => 18, 'actif' => true],

            ['nom' => 'Tanzanie', 'code' => 'TZ', 'devise' => 'TZS', 'tva_par_defaut' => 18, 'actif' => true],
            ['nom' => 'Ouganda', 'code' => 'UG', 'devise' => 'UGX', 'tva_par_defaut' => 18, 'actif' => true],
        ];

        foreach ($countries as $c) {
            Pays::updateOrCreate(
                ['code' => $c['code']],
                $c
            );
        }
    }
}
