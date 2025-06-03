<?php

namespace Webkul\Marketplace\Http\Controllers\Admin;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Webkul\Admin\Http\Requests\MassDestroyRequest;
use Webkul\Admin\Http\Requests\MassUpdateRequest;
use Webkul\Marketplace\DataGrids\Admin\SellerDataGrid;
use Webkul\Marketplace\DataGrids\Admin\SellerFlagsDataGrid;
use Webkul\Marketplace\Enums\Order;
use Webkul\Marketplace\Http\Requests\ProductFromRequest;
use Webkul\Marketplace\Http\Requests\SellerFormRequest;
use Webkul\Marketplace\Mail\SellerApprovalNotification;
use Webkul\Marketplace\Mail\SellerDeleteNotification;
use Webkul\Marketplace\Repositories\OrderRepository;
use Webkul\Marketplace\Repositories\ProductRepository;
use Webkul\Marketplace\Repositories\SellerRepository;
use Webkul\Product\Repositories\ProductRepository as BaseProductRepository;

class SellerController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected SellerRepository $sellerRepository,
        protected OrderRepository $orderRepository,
        protected ProductRepository $productRepository,
        protected BaseProductRepository $baseProductRepository
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(): View|JsonResponse
    {
        if (request()->ajax()) {
            return datagrid(SellerDataGrid::class)->process();
        }

        return view('marketplace::admin.sellers.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SellerFormRequest $request): JsonResponse
    {
        $this->sellerRepository->create(array_merge($request->validated(), [
            'password' => rand(100000, 10000000),
            'address'  => implode(PHP_EOL, request('address')),
        ]));

        return new JsonResponse([
            'message' => trans('marketplace::app.admin.sellers.index.create.success'),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id): View|JsonResponse
    {
        $seller = $this->sellerRepository->findOrFail($id);

        return view('marketplace::admin.sellers.edit')
            ->with('seller', $seller);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SellerFormRequest $request, int $id): JsonResponse
    {
        $this->sellerRepository->findOrFail($id);

        $data = array_merge($request->validated(), [
            'address'      => implode(PHP_EOL, request('address')),
            'is_suspended' => empty(request('is_suspended')) ? 0 : request('is_suspended'),
        ]);

        if (empty($data['commission_enable'])) {
            $data['commission_enable'] = 0;

            $data['commission_percentage'] = 0;
        }

        if (empty($data['allowed_product_types'])) {
            $data['allowed_product_types'] = null;
        }

        $this->sellerRepository->update($data, $id);

        session()->flash('success', trans('marketplace::app.admin.sellers.edit.update-success'));

        return new JsonResponse([
            'redirect_url' => route('admin.marketplace.sellers.index'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): JsonResponse
    {
        try {
            $seller = $this->sellerRepository->find($id);

            $pendingOrdersCount = $seller->orders()
                ->whereIn('status', [
                    Order::STATUS_PENDING->value,
                    Order::STATUS_PROCESSING->value,
                ])
                ->count();

            if ($pendingOrdersCount) {
                return new JsonResponse([
                    'message' => trans('marketplace::app.admin.sellers.index.pending-orders'),
                ], 500);
            }

            $this->sellerRepository->delete($id);

            try {
                Mail::queue(new SellerDeleteNotification($seller->toArray()));
            } catch (\Exception $e) {
            }

            return new JsonResponse([
                'message' => trans('marketplace::app.admin.sellers.index.delete-success'),
            ], 200);
        } catch (\Exception $e) {
            return new JsonResponse([
                'message' => trans('marketplace::app.admin.sellers.index.delete-failed'),
            ], 500);
        }
    }

    /**
     * Mass delete the sellers.
     */
    public function massDestroy(MassDestroyRequest $request): JsonResponse
    {
        $sellerIds = $request->input('indices');

        $orderCount = $this->orderRepository
            ->whereIn('marketplace_seller_id', $sellerIds)
            ->whereIn('status', [
                Order::STATUS_PENDING->value,
                Order::STATUS_PROCESSING->value,
            ])
            ->count();

        if ($orderCount) {
            return new JsonResponse([
                'message' => trans('marketplace::app.admin.sellers.index.pending-orders'),
            ], 500);
        }

        try {
            foreach ($sellerIds as $id) {
                $seller = $this->sellerRepository->find($id);

                if (isset($seller)) {
                    $this->sellerRepository->delete($id);

                    try {
                        Mail::queue(new SellerDeleteNotification($seller->toArray()));
                    } catch (\Exception $e) {
                    }
                }
            }

            return new JsonResponse([
                'message' => trans('marketplace::app.admin.sellers.index.delete-success'),
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Mass update the reviews.
     */
    public function massUpdate(MassUpdateRequest $request): JsonResponse
    {
        $sellers = $this->sellerRepository
            ->whereIn('id', $request->input('indices'))
            ->get();

        foreach ($sellers as $seller) {
            if ($seller->is_approved == $request->input('value')) {
                continue;
            }

            Event::dispatch('marketplace.seller.update.before', $seller->id);

            $seller->update([
                'is_approved' => $request->input('value'),
            ]);

            Event::dispatch('marketplace.seller.update.after', $seller->refresh());

            try {
                Mail::to($seller->email)
                    ->send(new SellerApprovalNotification($seller));
            } catch (\Exception $e) {
            }
        }

        return new JsonResponse([
            'message' => trans('marketplace::app.admin.sellers.index.update-success'),
        ], 200);
    }

    /**
     * Display a listing of the resource.
     */
    public function flags(int $id): View|JsonResponse
    {
        $seller = $this->sellerRepository->findOrFail($id);

        if (request()->ajax()) {
            return datagrid(SellerFlagsDataGrid::class)->process();
        }

        return view('marketplace::admin.sellers.flags.index')
            ->with('seller', $seller);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function search($id): View|JsonResponse|RedirectResponse
    {
        $seller = $this->sellerRepository->findOrFail($id);

        if (! $seller->is_profile_completed) {
            return back()->with('warning', trans('marketplace::app.admin.sellers.index.incomplete-profile'));
        }

        if (request()->input('query')) {
            $results = [];

            $products = $this->productRepository->searchProducts(request()->input('query'), $seller);

            foreach ($products as $row) {
                $results[] = [
                    'id'              => $row->id,
                    'sku'             => $row->sku,
                    'name'            => $row->name,
                    'formatted_price' => core()->formatBasePrice($row->getTypeInstance()->getMinimalPrice()),
                    'base_image'      => $row->images->first()
                        ? $row->images->first()->url
                        : null,
                ];
            }

            return new JsonResponse($results);
        } else {
            return view('marketplace::admin.sellers.products.search');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function assignProduct(int $sellerId, int $productId): View|RedirectResponse
    {
        $seller = $this->sellerRepository->findOrFail($sellerId);

        $baseProduct = $this->baseProductRepository->findOrFail($productId);

        $product = $this->productRepository->findOneWhere([
            'product_id'            => $productId,
            'marketplace_seller_id' => $sellerId,
        ]);

        if ($product) {
            return back()->with('error', trans('marketplace::app.admin.sellers.assign-product.already-selling'));
        }

        if (! $this->sellerRepository->getAllowedProducts($seller)->has($baseProduct->type)) {
            return back()->with('error', trans('marketplace::app.admin.sellers.assign-product.product-not-allowed', [
                'type' => trans(config('product_types')[$baseProduct->type]['name']),
            ]));
        }

        return view('marketplace::admin.sellers.products.assign')
            ->with([
                'baseProduct'   => $baseProduct,
                'seller'        => $seller,
                'totalProducts' => $this->productRepository->getTotalProducts($seller, true),
            ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function saveAssignProduct(ProductFromRequest $request, int $sellerId, int $productId): RedirectResponse
    {
        $product = $this->productRepository->findOneWhere([
            'product_id'            => $productId,
            'marketplace_seller_id' => $sellerId,
        ]);

        if ($product) {
            return back()->with('error', trans('marketplace::app.admin.sellers.assign-product.already-selling'));
        }

        $this->productRepository->createAssign(array_merge($request->all(), [
            'product_id'            => $productId,
            'marketplace_seller_id' => $sellerId,
        ]));

        return to_route('admin.marketplace.sellers.index')
            ->with('success', trans('marketplace::app.admin.sellers.assign-product.assign-successfully'));
    }
}
