<?php

//namespace FlyerAlarmDigital\RabbitWhisper;
namespace App\Http\Controllers\Whisper;

use App\Http\Controllers\Controller;
use Log;
//use FlyerAlarmDigital\RabbitWhisper\CommoLog;
use App\CommoLog;

class AckController extends Controller
{
    protected $messageReceived;
    protected $messageType;
    protected $message_identifier;
    protected $sender;
    protected $receiver;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct($messageReceived)
    {
        $this->messageReceived = $messageReceived;
        $this->messageType = 'ACK'; // acknowlege

    }

    public function sendMessage(){

        $this->sender = env('APPID');
        $this->analyzeMessage();
        $returnMessage = $this->createMessage();
        $this->send($returnMessage);

        $this->logReturn($returnMessage);


    }

    private function analyzeMessage(){

        $messageArray = json_decode($this->messageReceived, true);
        $this->message_identifier = $messageArray['message_identifier'];
        $this->sender = $messageArray['sender'];
        $this->receiver = $messageArray['receiver'];
        $this->message = json_encode($messageArray['message']);
        $this->messageType = 'RECEIVE';
        $this->logMessage();
    }

    private function logMessage(){

        $msg = new CommoLog;
        $msg->message_identifier = $this->message_identifier;
        $msg->receiver = $this->receiver;
        $msg->sender = $this->sender;
        $msg->message = $this->messageReceived;
        $msg->type = $this->messageType;
        $msg->save();
    }

    private function createMessage(){

        $mid = ['message_identifier' => $this->message_identifier, 'sender' => config('whisper.appid'), 'receiver' => $this->receiver, 'message' => 'ACK'];
        return json_encode($mid);
    }

    /**
     * Actually send the message
     * @return \Illuminate\Http\JsonResponse
     */
    private function send($returnMessage){

        try{
            \Amqp::publish($this->receiver, $returnMessage, ['queue' => $this->receiver]);

        }catch(Exception $e){
            return response()->json(['status' => 'fail', 'message' => 'Rabbit Publishing failed with: '.$e]);
        }


    }

    private function logReturn($returnMessage){
        $this->receiver = $this->sender;
        $this->sender = env('APPID');
        $this->messageReceived = $returnMessage;
        $this->messageType = 'ACK';
        $this->logMessage();
    }

}

