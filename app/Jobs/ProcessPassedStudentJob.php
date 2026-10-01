<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessPassedStudentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

        public $batchId;
        public $row;

        public function __construct($batchId, $row)
        {
            $this->batchId = $batchId;
            $this->row = $row;
        }

        public function handle()
        {
            $batch = ImportBatch::find($this->batchId);

            try {
                // 👇 your existing student + email logic here
                // create student
                // Mail::to(...)->send(...);

                $batch->increment('processed');

            } catch (\Exception $e) {
                $batch->increment('failed');
            }

            if ($batch->processed + $batch->failed >= $batch->total) {
                $batch->update(['status' => 'completed']);
            }
    }
}