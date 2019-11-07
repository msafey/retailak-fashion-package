<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ContactusMail extends Mailable
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

        $name = $requestData['name'];
        $email = $requestData['email'];
        $subject = $requestData['subject'];
        $body = $requestData['message'];

        return $this->from($email)->view('mail.contactus', compact( 'name' , 'email', 'subject', 'body'))
            ->to('khotwh.website@gmail.com')->subject("Khotwh  Website");

    }


}
