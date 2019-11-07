<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AutoReplyMail extends Mailable
{
    use Queueable, SerializesModels;


    protected $requestData = [];

    public function __construct($requestData)
    {
        $this->requestData = $requestData;
    }


    public function build()
    {
        $requestData = $this->requestData;
        $email = $requestData['email'];
        return $this->from($email)->view('mail.auto-reply')->to($email)->subject("Khotwh Website");

    }


}
