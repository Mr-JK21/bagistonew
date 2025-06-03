<?php

namespace Webkul\Marketplace\Helpers\Fakers;

use Illuminate\Database\Eloquent\Collection;
use Webkul\Marketplace\Models\Seller as SellerModel;

class Seller
{
    /**
     * Create a customers.
     */
    public function create(int $count): Collection
    {
        return $this->factory()
            ->count($count)
            ->create();
    }

    /**
     * Get the factory instance.
     */
    public function factory()
    {
        return SellerModel::factory();
    }
}
