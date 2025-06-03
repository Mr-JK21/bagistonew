<?php

namespace Webkul\Marketplace\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Webkul\Marketplace\Models\Seller;

class SellerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Seller::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name'                  => $this->faker->name,
            'email'                 => $this->faker->unique()->safeEmail,
            'password'              => Hash::make($this->faker->password),
            'url'                   => $this->faker->unique()->slug,
            'shop_title'            => $this->faker->companySuffix,
            'address'               => $this->faker->address,
            'phone'                 => $this->faker->unique()->numerify('##########'),
            'state'                 => $this->faker->state,
            'city'                  => $this->faker->city,
            'country'               => $this->faker->countryCode,
            'postcode'              => $this->faker->postcode,
            'description'           => $this->faker->paragraph,
            'is_approved'           => 1,
            'commission_enable'     => 1,
            'commission_percentage' => $this->faker->randomFloat(2, 0, 40),
        ];
    }
}
