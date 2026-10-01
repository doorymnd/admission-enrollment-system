<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminPasswordResetMail extends Mailable
{
    use Queueable, SerializesModels;

    public $admin;
    public $newPassword;


    public function __construct($admin, $newPassword)
    {
        $this->admin = $admin;
        $this->newPassword = $newPassword;
    }


    public function build()
    {
        return $this->subject('Your Admin Password Has Been Reset')
            ->view('emails.resetPasswordMail');
    }
}
