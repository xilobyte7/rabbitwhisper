<?php

namespace FlyerAlarmDigital\RabbitWhisper\Facades;

use Illuminate\Support\Facades\Facade;


class Whisper extends Facade
{

    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'Whisper';
    }
}
