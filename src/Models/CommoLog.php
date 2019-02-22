<?php

namespace FlyerAlarmDigital\RabbitWhisper;

use Illuminate\Database\Eloquent\Model;

class CommoLog extends Model
{
    protected $table = 'commo_logs';

    protected $fillable = [
        'message_identifier','receiver','sender','message','type'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [

    ];



}
