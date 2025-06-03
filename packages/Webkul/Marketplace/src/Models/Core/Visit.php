<?php

namespace Webkul\Marketplace\Models\Core;

use Webkul\Core\Models\Visit as BaseVisit;

class Visit extends BaseVisit
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'method',
        'request',
        'url',
        'referer',
        'languages',
        'useragent',
        'headers',
        'device',
        'platform',
        'browser',
        'ip',
        'visitable_type',
        'visitable_id',
        'visitor_id',
        'visitor_type',
        'channel_id',
        'marketplace_seller_id',
    ];
}
