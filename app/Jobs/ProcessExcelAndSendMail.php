<?php

namespace App\Jobs;

use App\Mail\CreateStudentAccountMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class ProcessExcelAndSendMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $email;
    protected $data;

    public function __construct($email, $data)
    {
        $this->email = $email;
        $this->data = $data;
    }

    public function handle()
    {
        // Do Excel logic here if needed (save students, etc.)

        // Send email (QUEUED)
        Mail::to($this->email)->send(
            new CreateStudentAccountMail($this->data)
        );
    }
}
