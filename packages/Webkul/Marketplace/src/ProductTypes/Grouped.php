<?php

namespace Webkul\Marketplace\ProductTypes;

use Webkul\Product\Type\Grouped as BaseGroupedType;

class Grouped extends BaseGroupedType
{
    /**
     * Add product. Returns error message if can't prepare product.
     *
     * @param  array  $data
     * @return array|string
     */
    public function prepareForCart($data)
    {
        if (
            ! isset($data['qty'])
            || ! is_array($data['qty'])
        ) {
            return trans('product::app.checkout.cart.missing-options');
        }

        $cartProductsList = [];

        foreach ($data['qty'] as $productId => $qty) {
            if (! $qty) {
                continue;
            }

            $product = $this->productRepository->find($productId);

            $cartProducts = $product->getTypeInstance()->prepareForCart([
                'product_id' => $productId,
                'quantity'   => $qty,
            ]);

            if (is_string($cartProducts)) {
                return $cartProducts;
            }

            foreach ($cartProducts as &$cartProduct) {
                if (! empty($data['seller_info'])) {
                    $cartProduct['additional']['seller_info'] = $data['seller_info'];

                    if (! empty($data['attributes'])) {
                        $cartProduct['additional']['attributes'] = ! empty($cartProduct['additional']['attributes'])
                            ? array_merge($cartProduct['additional']['attributes'], $data['attributes'])
                            : $data['attributes'];
                    }
                }
            }

            $cartProductsList[] = $cartProducts;
        }

        $products = array_merge(...$cartProductsList);

        if (! count($products)) {
            return trans('product::app.checkout.cart.integrity.qty-missing');
        }

        return $products;
    }
}
