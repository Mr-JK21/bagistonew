<?php

namespace Webkul\Marketplace\Helpers\Fakers;

use Illuminate\Database\Eloquent\Collection;

class Faker
{
    /**
     * Contains faker classes.
     */
    protected array $entities = [
        'sellers'  => Seller::class,
    ];

    /**
     * Fake data.
     */
    public function fake(string $entity, int $count): Collection
    {
        return app($this->entities[$entity])->create($count);
    }
}
