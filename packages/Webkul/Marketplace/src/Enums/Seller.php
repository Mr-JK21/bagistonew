<?php

namespace Webkul\Marketplace\Enums;

enum Seller
{
    case APPROVED;
    case DISAPPROVED;
    case IS_SUSPENDED;
    case IS_NOT_SUSPENDED;

    public function value(): int
    {
        return match ($this) {
            Seller::APPROVED, Seller::IS_NOT_SUSPENDED => 1,
            Seller::DISAPPROVED, Seller::IS_SUSPENDED => 0,
        };
    }
}
