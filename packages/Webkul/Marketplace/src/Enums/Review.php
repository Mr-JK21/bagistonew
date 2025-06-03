<?php

namespace Webkul\Marketplace\Enums;

enum Review: string
{
    case STATUS_PENDING = 'pending';
    case STATUS_APPROVED = 'approved';
    case STATUS_DISAPPROVED = 'disapproved';
}
