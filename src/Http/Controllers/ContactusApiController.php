<?php


namespace App\Http\Controllers;

use App\Http\Controllers\ApiController;
use App\Mail\AutoReplyMail;
use App\Mail\ContactusMail;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;


class ContactusApiController extends ApiController
{
    public function sendEmail(Request $request)
    {

        $requestData = $request->only('name', 'email', 'subject', 'message');
        Mail::send(new ContactusMail($requestData));
        Mail::send(new AutoReplyMail($requestData));
        return response()->json('message send successfully',200);
    }


}
