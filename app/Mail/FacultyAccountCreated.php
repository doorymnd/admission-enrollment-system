<?php

namespace App\Mail;

use App\Models\Admin\Faculty;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FacultyAccountCreated extends Mailable
{
    use Queueable, SerializesModels;

    public $faculty;
    public $temporaryPassword;

    public function __construct(Faculty $faculty, $temporaryPassword)
    {
        $this->faculty = $faculty;
        $this->temporaryPassword = $temporaryPassword;
    }

    public function build()
    {
        return $this->subject('Your Faculty Account')
                    ->view('emails.FacultyAccountCreated');
    }
}
