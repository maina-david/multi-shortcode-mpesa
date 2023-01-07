<?php

namespace MainaDavid\MultiShortcodeMpesa\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ShortCode extends Model
{
    protected $fillable = [
        'environment',
        'direction',
        'shortcode',
        'consumer_key',
        'consumer_secret',
        'pass_key',
        'initiator_name',
        'initiator_password'
    ];
}