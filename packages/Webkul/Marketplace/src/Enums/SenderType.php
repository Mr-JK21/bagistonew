<?php

namespace Webkul\Marketplace\Enums;

enum SenderType: string
{
    case ADMIN = 'admin';
    case SELLER = 'seller';

    /**
     * Get the values of the enum.
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
