##FlyerAlarm Digital's RabbitWhisper


Environment:
- APPID
- FIRE_SEQUENCE



####Sending a Message:

    $commo = new SendController( $receiver , $message , $type = 'SEND' );
    $commo->sendMessage();

####Receiving a message:


    public function handle()
        {
            $appid = trim(env('APPID'));
    
            \Amqp::consume($appid, function ($message, $resolver) {
                $thisMessage = json_decode($message->body, true);
                $resolver->acknowledge($message);
    
                $ack = new AckController($thisMessage);
                $ack->sendMessage();
    
                $processMe = new WhisperProcessController($thisMessage);
                $processMe->processMessage();
    
            }, [
                'exchange' => 'amq.direct',
                'exchange_type' => 'direct',
                'queue_force_declare' => true,
                'queue_exclusive' => false,
                'queue_durable' => true,
                'persistent' => true// required if you want to listen forever
            ]);
        }
