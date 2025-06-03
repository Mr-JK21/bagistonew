<?php

namespace Webkul\Marketplace\Enums;

enum FlagReasonType: string
{
    case PRODUCT = 'product';
    case SELLER = 'seller';

    /**
     * Get the values of the enum.
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
