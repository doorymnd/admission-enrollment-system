<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Admission\Admission;
use Carbon\Carbon;

class CleanupExpiredAdmissions extends Command
{
    protected $signature = 'admissions:cleanup';
    protected $description = 'Delete pending admissions older than 2 weeks';

    public function handle()
    {
        $deleted = Admission::where('status', 'pending')
            ->where('created_at', '<=', Carbon::now()->subDays(14))
            ->delete();

        $this->info("Deleted {$deleted} expired admissions.");
    }
}
