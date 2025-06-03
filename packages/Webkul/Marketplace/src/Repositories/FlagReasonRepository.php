<?php

namespace Webkul\Marketplace\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\Marketplace\Contracts\MpFlagReason;

class FlagReasonRepository extends Repository
{
    /**
     * Specify Model class name
     */
    public function model(): string
    {
        return MpFlagReason::class;
    }
}
