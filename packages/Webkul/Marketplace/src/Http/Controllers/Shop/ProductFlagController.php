<?php

namespace Webkul\Marketplace\Http\Controllers\Shop;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Webkul\Marketplace\Mail\ProductReportNotification;
use Webkul\Marketplace\Repositories\OrderItemRepository;
use Webkul\Marketplace\Repositories\ProductFlagRepository;
use Webkul\Marketplace\Repositories\ProductRepository;
use Webkul\Shop\Http\Controllers\Controller;

class ProductFlagController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected ProductRepository $productRepository,
        protected OrderItemRepository $orderItemRepository,
        protected ProductFlagRepository $productFlagRepository,
    ) {}

    /**
     * Create product flag record.
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'reason'                 => 'required',
            'marketplace_product_id' => 'required',
            'other_reason'           => 'required_if:reason,other',
        ]);

        $product = $this->productRepository->findOrFail($data['marketplace_product_id']);

        if (! auth()->guard('customer')->check()) {
            return new JsonResponse([
                'type'    => 'warning',
                'message' => trans('marketplace::app.shop.products.flag.guest-alert'),
            ]);
        }

        $customer = auth()->guard('customer')->user();

        $alreadyReported = $this->productFlagRepository->findOneWhere([
            'marketplace_product_id' => $product->id,
            'customer_id'            => $customer->id,
        ]);

        if ($alreadyReported) {
            return new JsonResponse([
                'type'    => 'warning',
                'message' => trans('marketplace::app.shop.products.flag.already-reported'),
            ]);
        }

        if (! $this->orderItemRepository->isCustomerOrderedProduct($product, $customer)) {
            return new JsonResponse([
                'type'    => 'warning',
                'message' => trans('marketplace::app.shop.products.flag.not-allowed'),
            ]);
        }

        Event::dispatch('shop.marketplace.products.flag.create.before');

        if ($data['reason'] === 'other') {
            $data['reason'] = $data['other_reason'];
        }

        $flag = $this->productFlagRepository->create(array_merge($data, [
            'name'                  => $customer->name,
            'email'                 => $customer->email,
            'customer_id'           => $customer->id,
            'marketplace_seller_id' => $product->marketplace_seller_id,
        ]));

        $flag = $flag->load(['product.product', 'seller', 'customer']);

        try {
            Mail::to($flag->seller->email)
                ->send(new ProductReportNotification($flag, true));

            Mail::to(core()->getAdminEmailDetails()['email'])
                ->send(new ProductReportNotification($flag));
        } catch (\Exception $e) {
        }

        Event::dispatch('shop.marketplace.products.flag.create.after', $flag);

        return new JsonResponse([
            'type'    => 'success',
            'message' => trans('marketplace::app.shop.products.flag.create-success'),
        ]);
    }
}
