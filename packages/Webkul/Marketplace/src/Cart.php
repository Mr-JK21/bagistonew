<?php

namespace Webkul\Marketplace;

use Illuminate\Support\Facades\Event;
use Webkul\Checkout\Cart as BaseCart;
use Webkul\Checkout\Contracts\Cart as CartContract;
use Webkul\Checkout\Contracts\CartItem;
use Webkul\Marketplace\Helpers\Cart as CartHelper;
use Webkul\Marketplace\Repositories\ProductRepository;
use Webkul\Marketplace\Repositories\SellerRepository;
use Webkul\Product\Contracts\Product;

class Cart extends BaseCart
{
    /**
     * Seller id.
     *
     * @var int
     */
    public $sellerId = 0;

    /**
     * Check current product seller product.
     */
    public function getCurrentProductSellerId(): int
    {
        return $this->sellerId;
    }

    /**
     * Get cart item by product.
     */
    public function getItemByProduct(array $data, ?array $parentData = null): ?CartItem
    {
        $items = $this->getCart()->all_items;

        foreach ($items as $item) {
            if ($item->product->getTypeInstance()->compareOptions($item->additional, $data['additional'])) {
                if (! isset($data['additional']['parent_id'])) {
                    if ($item->additional['product_id'] == $data['additional']['product_id']) {
                        return $item;
                    }
                }

                if ($item->parent->product->getTypeInstance()->compareOptions($item->parent->additional, $parentData ?: request()->all())) {
                    return $item;
                }
            } elseif (
                isset($data['additional']['seller_info'])
                && isset($item->additional['seller_info'])
            ) {
                $sellerInfo = $data['additional']['seller_info'];

                if (
                    ($sellerInfo['product_id'] == $item->additional['seller_info']['product_id'])
                    && ($sellerInfo['seller_id'] == $item->additional['seller_info']['seller_id'])
                ) {
                    return $item;
                }
            }
        }

        return null;
    }

    /**
     * Add items in a cart with some cart and item details.
     */
    public function addProduct(Product $product, array $data): CartContract|\Exception
    {
        $this->sellerId = 0;

        Event::dispatch('checkout.cart.add.before', $product->id);

        if (! $cart = $this->getCart()) {
            $cart = $this->createCart([]);
        }

        if (! empty($data['seller_info'])) {
            $this->sellerId = $data['seller_info']['seller_id'];
        } else {
            $sellerProduct = app(ProductRepository::class)->findOneWhere([
                'product_id' => $product->id,
                'is_owner'   => 1,
            ]);

            if ($sellerProduct) {
                $this->sellerId = $sellerProduct->marketplace_seller_id;

                $additional = app(CartHelper::class)->getAdditionalData($sellerProduct);

                $data = array_merge($data, $additional);
            }
        }

        $cartProducts = $product->getTypeInstance()->prepareForCart($data);

        if (is_string($cartProducts)) {
            if (! $cart->all_items->count()) {
                $this->removeCart($cart);
            } else {
                $this->collectTotals();
            }

            throw new \Exception($cartProducts);
        } else {
            $parentCartItem = null;

            foreach ($cartProducts as $cartProduct) {
                $cartItem = $this->getItemByProduct($cartProduct, $data);

                if (isset($cartProduct['parent_id'])) {
                    $cartProduct['parent_id'] = $parentCartItem->id;
                }

                if (! $cartItem) {
                    $cartItem = $this->cartItemRepository->create(array_merge($cartProduct, ['cart_id' => $cart->id]));
                } else {
                    if (
                        isset($cartProduct['parent_id'])
                        && $cartItem->parent_id !== $parentCartItem->id
                    ) {
                        $cartItem = $this->cartItemRepository->create(array_merge($cartProduct, [
                            'cart_id' => $cart->id,
                        ]));
                    } else {
                        $cartItem = $this->cartItemRepository->update($cartProduct, $cartItem->id);
                    }
                }

                if (! $parentCartItem) {
                    $parentCartItem = $cartItem;
                }
            }
        }

        $this->collectTotals();

        Event::dispatch('checkout.cart.add.after', $cart);

        return $this->getCart();
    }

    /**
     * Update cart items information.
     */
    public function updateItems(array $data): bool|\Exception
    {
        foreach ($data['qty'] as $itemId => $quantity) {
            $item = $this->cartItemRepository->find($itemId);

            if (! $item) {
                continue;
            }

            $this->sellerId = empty($item->additional['seller_info']) ? 0 : $item->additional['seller_info']['seller_id'];

            if (! $item->product->status) {
                throw new \Exception(__('shop::app.checkout.cart.inactive'));
            }

            if ($quantity <= 0) {
                $this->removeItem($itemId);

                throw new \Exception(__('shop::app.checkout.cart.illegal'));
            }

            $item->quantity = $quantity;

            if (! $this->isItemHaveQuantity($item)) {
                throw new \Exception(__('shop::app.checkout.cart.inventory-warning'));
            }

            Event::dispatch('checkout.cart.update.before', $item);

            $this->cartItemRepository->update([
                'quantity'            => $quantity,
                'total'               => core()->convertPrice($item->base_price * $quantity),
                'total_incl_tax'      => core()->convertPrice($item->base_price_incl_tax * $quantity),
                'base_total'          => $item->base_price * $quantity,
                'base_total_incl_tax' => $item->base_price_incl_tax * $quantity,
                'total_weight'        => $item->weight * $quantity,
                'base_total_weight'   => $item->weight * $quantity,
                'additional'          => [
                    ...$item->additional,
                    'quantity' => $quantity,
                ],
            ], $itemId);

            Event::dispatch('checkout.cart.update.after', $item);
        }

        $this->collectTotals();

        return true;
    }

    /**
     * Get Cart Errors.
     */
    public function getErrors()
    {
        if (! $this->getCart()) {
            return [
                'error_code' => 'CART_NOT_FOUND',
                'message'    => trans('shop::app.checkout.cart.index.empty-product'),
            ];
        }

        if (! $this->isItemsHaveSufficientQuantity()) {
            return [
                'error_code' => 'INSUFFICIENT_QUANTITY',
                'message'    => trans('shop::app.checkout.cart.inventory-warning'),
            ];
        }

        if (! $this->haveMinimumOrderAmount()) {
            $minimumOrderDescription = core()->getConfigData('sales.order_settings.minimum_order.description');

            return [
                'error_code' => 'MINIMUM_ORDER_AMOUNT',
                'message'    => $minimumOrderDescription ?: trans('shop::app.checkout.cart.minimum-order-message'),
                'amount'     => core()->formatPrice((int) core()->getConfigData('sales.order_settings.minimum_order.minimum_order_amount') ?: $this->getOrderAmount()),
            ];
        }

        return $this->haveSellerMinimumOrderAmount();
    }

    /**
     * Check if cart has seller-specific minimum order amount.
     */
    public function haveSellerMinimumOrderAmount(): array
    {
        if (
            ! core()->getConfigData('marketplace.settings.general.status')
            && ! core()->getConfigData('marketplace.settings.seller.enable_minimum_order_amount')
        ) {
            return [];
        }

        $cart = $this->getCart();

        $productsAmount = [];

        foreach ($cart->items as $item) {
            if (
                ! empty($item->additional['seller_info'])
                && ! empty($item->additional['seller_info']['seller_id'])
            ) {
                $sellerId = $item->additional['seller_info']['seller_id'];

                $productsAmount[$sellerId] = ($productsAmount[$sellerId] ?? 0) + $item->base_total;
            }
        }

        $errors = [];

        $sellers = app(SellerRepository::class)->findWhereIn('id', array_keys($productsAmount));

        foreach ($sellers as $seller) {
            if (
                $seller->min_order_amount
                && $productsAmount[$seller->id] < $seller->min_order_amount
            ) {
                $errors[] = [
                    'message'    => trans('marketplace::app.shop.checkout.cart.minimum-order-message', ['shop_title' => $seller->shop_title]),
                    'amount'     => core()->currency($seller->min_order_amount),
                ];
            }
        }

        if (count($errors)) {
            return [
                'error_code' => 'MARKETPLACE_MINIMUM_ORDER_AMOUNT',
                'errors'     => $errors,
            ];
        } else {
            return [];
        }
    }
}
