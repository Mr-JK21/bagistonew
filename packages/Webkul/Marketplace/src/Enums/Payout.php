<?php

namespace Webkul\Marketplace\Enums;

enum Payout: string
{
    case STATUS_PAID = 'paid';
    case STATUS_REFUNDED = 'refunded';
    case STATUS_REQUESTED = 'requested';
}
