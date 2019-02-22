<?php


//namespace FlyerAlarmDigital\RabbitWhisper;
namespace App\Http\Controllers\Whisper;




class WhisperProcessController
{
    protected $message;
    protected $message_sender;

    /**
     * This controll will receive RabbitWhisper messages. Here you may add code to
     * process them as you see fit.
     */

    public function __construct($message)
    {
        $this->message = $message;

    }

    public function processMessage(){
        dd('MESSAGE IS PROCESSED');
    }

}