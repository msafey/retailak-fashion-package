<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PurchaseInvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $location;
    protected $email;
    protected $body;
    public function __construct($location,$email,$body)
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
        // dd($email);
        return $this->view('mail.test',compact('location','body'))->to($email)->subject("Purchase Invoice")->attach($location);
    }
}
