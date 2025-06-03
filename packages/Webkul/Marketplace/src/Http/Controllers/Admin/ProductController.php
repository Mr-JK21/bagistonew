<?php

namespace Webkul\Marketplace\Http\Controllers\Admin;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Webkul\Admin\Http\Requests\MassDestroyRequest;
use Webkul\Admin\Http\Requests\MassUpdateRequest;
use Webkul\Marketplace\DataGrids\Admin\ProductDataGrid;
use Webkul\Marketplace\DataGrids\Admin\ProductFlagsDataGrid;
use Webkul\Marketplace\Mail\ProductApprovalNotification;
use Webkul\Marketplace\Repositories\ProductRepository;
use Webkul\Product\Repositories\ProductRepository as BaseProductRepository;

class ProductController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected BaseProductRepository $baseProductRepository,
        protected ProductRepository $productRepository
    ) {}

    /**
     * Display a listing of the resource.
     *
     * @return mixed
     */
    public function index()
    {
        if (request()->ajax()) {
            return datagrid(ProductDataGrid::class)->process();
        }

        return view('marketplace::admin.products.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        $product = $this->productRepository->findOrFail($id);

        if ($product->is_owner) {
            Event::dispatch('catalog.product.delete.before', $product->product_id);

            $this->baseProductRepository->delete($product->product_id);

            Event::dispatch('catalog.product.delete.after', $product->product_id);
        } else {
            $this->productRepository->delete($id);
        }

        return new JsonResponse([
            'message' => trans('marketplace::app.admin.products.index.delete-success'),
        ], 200);
    }

    /**
     * Mass delete the product.
     */
    public function massDestroy(MassDestroyRequest $request): JsonResponse
    {
        try {
            foreach ($request->input('indices') as $id) {
                $product = $this->productRepository->find($id);

                if (! $product) {
                    continue;
                }

                if ($product->is_owner) {
                    Event::dispatch('catalog.product.delete.before', $product->product_id);

                    $this->baseProductRepository->delete($product->product_id);

                    Event::dispatch('catalog.product.delete.after', $product->product_id);
                } else {
                    $this->productRepository->delete($id);
                }
            }

            return new JsonResponse([
                'message' => trans('marketplace::app.admin.products.index.delete-success'),
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
        $products = $this->productRepository
            ->with('seller')
            ->whereIn('id', $request->input('indices'))
            ->get();

        foreach ($products as $product) {
            if (
                $product->is_approved == $request->input('value')
                || ! $product->seller->is_approved
            ) {
                continue;
            }

            Event::dispatch('marketplace.product.update.before', $product->id);

            $product->update([
                'is_approved' => $request->input('value'),
            ]);

            Event::dispatch('marketplace.product.update.after', $product->refresh());

            try {
                Mail::queue(new ProductApprovalNotification($product));
            } catch (\Exception $e) {
            }
        }

        return new JsonResponse([
            'message' => trans('marketplace::app.admin.products.index.update-success'),
        ], 200);
    }

    /**
     * Display a listing of the resource.
     */
    public function flags(int $id): View|JsonResponse
    {
        $product = $this->productRepository->findOrFail($id);

        if (request()->ajax()) {
            return datagrid(ProductFlagsDataGrid::class)->process();
        }

        return view('marketplace::admin.products.flags.index')
            ->with('product', $product);
    }
}
