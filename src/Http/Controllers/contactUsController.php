<?php

namespace App\Http\Controllers;

use App\Models\contactus;
use Validator;
use Response;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

class contactUsController extends Controller
{
    //
    public function store(Request $request)
    {

        // validate data in post
        $validate = Validator::make($request->all(),[
            'name' => 'required|string',
            'email' => 'required|string|email',
            'message' => 'required|string' ,
        ]);
        if ($validate->fails()) {
            $errors = $validate->errors();
            return Response::json($errors, 503);
        }

        // save obj
        $contac = new contactus();
        $contac->name = $request->name;
        $contac->email = $request->email;
        $contac->message = $request->message;
        $check = $contac->save();
        if($check)
        {
            // Send Mail
            $to_name = $request->name;
            $to_email = $request->email;
            Mail::send(['html' => 'mail.mailTemplate'], ['name' => $request->name , 'email'=>$request->email , 'msg'=> $request->message],
                function($message) use ($to_name, $to_email) {
                $message->to('khotwh.retailk@gmail.com', 'Retailk - Fridge')
                    ->subject('Contact us message - Fridge ');
                $message->from($to_email,$to_name);
            });

            return Response::json(['Status'=>'Success' , 'message'=>'contact us send Successfullys'] , 200);
        }
        else
        {
            return Response::json(['Status'=>'fail' , 'message'=>'failed to send message , pls try again later'] , 401);
        }
    }
}
