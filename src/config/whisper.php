<?php

return [

    /**
     * Rabbit Whisper
     * APPID is the name or identification number used to address this
     * application when using direct communications. This should not
     * ever change. If so, then you must make changes throughout all
     * applications that require commo with this APP.
     */

    /**
     * This is the address or token id of this APP. RabbitListener will
     * get all messages sent to this channel
     **/
    'appid' => env('APPID'),

    /**
     * So the CommoLog table does not get too full, how often do you
     * want to prune old logs? (days)
     */
    'commolog_prune' => 14,



];
