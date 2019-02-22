<?php

namespace FlyerAlarmDigital\RabbitWhisper;

use Illuminate\Console\Command;
use App\Http\Controllers\CommoController;
use FlyerAlarmDigital\RabbitWhisper\Controllers\SendController;

class RabbitListener extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rabbit:listen';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Listens to all RabbitMQ order messages assigned direct to me';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        \Amqp::consume(env('APPID'), function ($message, $resolver) {
            $thisMessage = json_decode($message->body, true);
            $resolver->acknowledge($message);

            $ack = new CommoController('', $thisMessage, $type = 'RECEIVE');
            $ack->receiveMessage();

        }, [
            'exchange' => env('RABBIT_EXCHANGE'),
            'exchange_type' => 'direct',
            'queue_force_declare' => true,
            'queue_exclusive' => false,
            'queue_durable' => true,
            'persistent' => true// required if you want to listen forever
        ]);
    }


}
