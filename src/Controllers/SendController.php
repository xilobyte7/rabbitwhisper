<?php

namespace FlyerAlarmDigital\RabbitWhisper;
use App\Http\Controllers\Controller;
use Request;
use Log;
use FlyerAlarmDigital\RabbitWhisper\CommoLog;

class SendController extends Controller
{
    protected $exchange;
    protected $to;
    protected $message;
    protected $messageType;
    protected $message_identifier;
    protected $sender;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct($receiver = '', $message = '', $type = 'SEND' )
    {
        $this->exchange = env('RABBIT_EXCHANGE');
        $this->receiver = $receiver;
        $this->message = $message;
        $this->messageType = $type;

    }

    public function sendMessage(){

        $this->sender = env('APPID');
        $message = $this->createMessage($this->message);
        $receiver = $this->sendTo();

        $this->send();
        Log::debug('Message Sent: TO: ' . $this->receiver . ' MESSAGE: '.$this->message);
        $this->logMessage();

    }

    private function logMessage(){

        $msg = new CommoLog;
        $msg->message_identifier = $this->message_identifier;

        $msg->receiver = $this->receiver;
        $msg->sender = $this->sender;
        $msg->message = $this->message;
        $msg->type = $this->messageType;
        $msg->save();
    }

    /**
     * Opportunity to add to the addressee list
     * @return mixed
     */
    private function sendTo(){

        return $this->receiver;
    }

    /**
     * Chance to alter the message in addition to what was sent over
     * @param $order_token
     * @return false|string
     */
    private function createMessage($message_identifier = NULL){

        if($message_identifier == NULL){

            $this->message_identifier = bin2hex(openssl_random_pseudo_bytes(12, $cstrong));
        }
        $mid = ['message_identifier' => $this->message_identifier, 'sender' => env('APPID'), 'receiver' => $this->receiver, 'message' => $this->message];
        $this->message = json_encode($mid);
        return true;
    }

    /**
     * Actually send the message
     * @return \Illuminate\Http\JsonResponse
     */
    private function send(){

        try{
            \Amqp::publish($this->receiver, json_encode($this->message), ['queue' => $this->receiver]);

        }catch(Exception $e){
            return response()->json(['status' => 'fail', 'message' => 'Rabbit Publishing failed with: '.$e]);
        }

    }

    public function receiveMessage(){


        $messageArray = json_decode($this->message, true);

        $this->message_identifier = $messageArray['message_identifier'];
        $this->sender = $messageArray['sender'];
        $this->receiver = $messageArray['receiver'];
        $this->message = json_encode($messageArray['message']);
        $this->messageType = 'RECEIVE';

        $this->logMessage();

        //Now REPLY
        $this->message = json_encode(['message' => 'ACKNOWLEGED']);
        $this->receiver = $messageArray['sender'];
        $this->messageType = 'ACKNOWLEGED';
        $this->sender = env('APPID');
        $this->createMessage($message_identifier = $messageArray['message_identifier']);
        $this->send();

        $this->logMessage();


    }



}

