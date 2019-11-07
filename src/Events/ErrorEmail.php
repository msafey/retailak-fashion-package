<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;

class ErrorEmail
{
    use  SerializesModels;


    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $error;

    public function __construct($error)
    {
//        dd($error);
        $this->error = $error;
    }

}
