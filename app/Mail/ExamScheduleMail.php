<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ExamScheduleMail extends Mailable
{
    use Queueable, SerializesModels;

    public $student;
    public $examDate;
    public $examTime;
    public $course;

    /**
     * Create a new message instance.
     */
    public function __construct($student, $examDate, $examTime, $course)
    {
        $this->student = $student;
        $this->examDate = $examDate;
        $this->examTime = $examTime;
        $this->course = $course;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Entrance Exam Schedule')
                    ->view('emails.exam_schedule');
    }
}