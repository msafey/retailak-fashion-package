<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SalesOrderMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
  protected $location;
  protected $email;
  protected $body;
    public function __construct($location,$body,$email)
    {
        $this->location = $location;
        $this->body = $body;
        $this->email = $email;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(){

         $location = $this->location;
         $body = $this->body;
         $email = $this->email;
         // dd($location);

          return $this->view('mail.test',compact('location','body'))->to('amamdouh@panarab-media.com')->subject("User Account Verification")->attach($location);
    }
}
