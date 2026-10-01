<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Admin\Course;

class CreateStudentAccountMail extends Mailable implements ShouldQueue
{
    use SerializesModels;

    public $first_name;
    public $last_name;
    public $email;
    public $password;
    public $course;

    public function __construct(
        $first_name, 
        $last_name, 
        $email, 
        $password,
        $course
    )
    {
        $this->first_name = $first_name;
        $this->last_name  = $last_name;
        $this->email      = $email;
        $this->password   = $password;
        $this->course = $course;
    }

    public function build()
    {
        return $this->subject('Your Student Account Credentials')
                    ->view('emails.StudentAccountCreated')
                    ->with([
                        'first_name' => $this->first_name,
                        'last_name'  => $this->last_name,
                        'email'      => $this->email,
                        'password'   => $this->password,
                        'course'     => $this->course ? $this->course->name : 'N/A',
                    ]);
    }
}