<?php

/**
 * Created by PhpStorm.
 * User: osama
 * Date: 8/28/2017
 * Time: 10:44 AM
 */
namespace App\Http;

use App\PushNorification;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;

class PushNotificationBase
{
    function __construct($targetDevices, $title, $message, $id,$data)
    {

        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60 * 20);

        $notificationBuilder = new PayloadNotificationBuilder($title);
        $notificationBuilder->setBody($message)
            ->setSound('default');

        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData($data);

        $option = $optionBuilder->build();
        $notification = $notificationBuilder->build();
        $setData = $dataBuilder->build();

        // You must change it to get your tokens
//        $tokens = MYDATABASE::pluck($token)->toArray();

        $downstreamResponse = FCM::sendTo($targetDevices, $option, $notification ,$setData);

        $success = $downstreamResponse->numberSuccess();
        $failure = $downstreamResponse->numberFailure();
        $downstreamResponse->numberModification();

        PushNorification::where('id',$id)->update(['push_success'=> $success,'push_failure'=> $failure]);

        return redirect('admin/pushtokens')->with('Push Notifcation Send Successfully');
    }

}