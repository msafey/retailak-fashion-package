<?php

namespace App\Listeners;

use App\Events\ErrorEmail;
use Illuminate\Support\Facades\Mail;

class SendErrorEmail
{
    /**
     * Handle the event.
     *
     * @param  ErrorEmail $event
     * @return void
     */



    public function handle(ErrorEmail $event)
    {
        // $emails = [ "ahakim@panarab-media.com","abeshir@panarab-media.com","amamdouh@panarab-media.com" ,"oyasser@panarab-media.com"];
        // $error = $event->error;
        
            // $data = array('emails' => $emails, 'error' => $error,
            // 'from' => 'onlinegomla@gmail.com',
            // 'from_name' => 'Gomla Online');
        
        

        // Mail::send('admin.email.error-report', $data, function ($message) use ($data) {
            // $message->to($data['emails'])->from($data['from'], $data['from_name'])->subject('Error in Gomla System');
        // });

    }
}
