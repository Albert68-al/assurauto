<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Police;
use Carbon\Carbon;

class UpdateExpiredPolices extends Command
{
    protected $signature = 'app:update-expired-polices';
    protected $description = 'Update police status when date_fin is passed';

    public function handle()
    {
        $now = Carbon::now();

        $updated = Police::where('statut', '!=', 'Expiré')
            ->where('date_fin', '<', $now)
            ->update(['statut' => 'Expiré']);

        $this->info("Updated $updated expired polices successfully.");

    }
}
