<?php

namespace Webkul\Marketplace\Http\Controllers\Shop;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Webkul\Checkout\Facades\Cart;
use Webkul\Marketplace\Helpers\Cart as CartHelper;
use Webkul\Marketplace\Repositories\ProductRepository;
use Webkul\Shop\Http\Controllers\Controller;
use Webkul\Shop\Http\Resources\CartResource;

class CartController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected ProductRepository $productRepository,
        protected CartHelper $cartHelper
    ) {}

    /**
     * Function for guests user to add the product in the cart.
     */
    public function store()
    {
        try {
            $id = request()->input('id');

            $productId = request()->input('product_id');

            $product = $id
                ? $this->productRepository->find($id)
                : $this->productRepository->findOneWhere(['product_id' => $productId]);

            if ($this->productRepository->isAvailableForSale($product) === false) {
                return new JsonResponse([
                    'message' => trans('marketplace::app.shop.checkout.cart.product-not-available'),
                ], 404);
            }

            $baseProduct = $product->product;

            $data = $this->cartHelper->getAdditionalData($product);

            if ($baseProduct->parent?->type === 'configurable') {
                $options = $this->cartHelper->getAdditionalOptions($baseProduct);

                $data['attributes'] = array_merge($data['attributes'], $options);
            }

            request()->merge($data);

            $cart = Cart::addProduct($product->product, request()->all());

            return new JsonResource([
                'data'    => new CartResource($cart),
                'message' => trans('shop::app.checkout.cart.item-add-to-cart'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'redirect_uri' => route('shop.product_or_category.index', $product->product->url_key),
                'message'      => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
