<?php

namespace Webkul\Marketplace\Listeners;

use Webkul\Checkout\Facades\Cart as CartFacade;
use Webkul\Marketplace\Repositories\ProductRepository;

class Cart
{
    /**
     * Create a new customer event listener instance.
     */
    public function __construct(protected ProductRepository $productRepository) {}

    /**
     * Collect totals before
     */
    public function collectTotalsBefore()
    {
        $cart = CartFacade::getCart();

        $cartItems = $cart->items;

        foreach ($cartItems as $item) {
            if (isset($item->additional['seller_info'])) {
                $product = $this->productRepository->findOneWhere([
                    'marketplace_seller_id' => $item->additional['seller_info']['seller_id'],
                    'id'                    => $item->additional['seller_info']['product_id'],
                ]);

                if (! $product) {
                    continue;
                }

                if (! $this->productRepository->isAvailableForSale($product)) {
                    CartFacade::removeItem($item->id);
                }

                if (! $item->additional['seller_info']['is_owner']) {
                    $item->price = core()->convertPrice($product->price);

                    $item->base_price = $product->price;
                    $item->custom_price = $product->price;
                    $item->total = core()->convertPrice($product->price * $item->quantity);
                    $item->base_total = $product->price * $item->quantity;

                    if ($item->product->type == 'downloadable') {
                        foreach ($product->downloadable_links as $link) {
                            if (! in_array($link->id, $item->additional['links'])) {
                                continue;
                            }

                            $item->price += core()->convertPrice($link->price);
                            $item->base_price += $link->price;
                            $item->custom_price += $link->price;
                            $item->total += (core()->convertPrice($link->price) * $item->quantity);
                            $item->base_total += ($link->price * $item->quantity);
                        }
                    }

                    $item->save();
                }
            }
        }
    }
}
