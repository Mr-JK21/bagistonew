<?php

namespace Webkul\Marketplace\Http\Controllers\Admin;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Event;
use Webkul\Admin\Http\Requests\MassDestroyRequest;
use Webkul\Admin\Http\Requests\MassUpdateRequest;
use Webkul\Marketplace\DataGrids\Admin\SellerReviewDataGrid;
use Webkul\Marketplace\Repositories\ProductRepository;
use Webkul\Marketplace\Repositories\ReviewRepository;
use Webkul\Product\Repositories\ProductFlatRepository;

class SellerReviewController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected ReviewRepository $reviewRepository,
        protected ProductRepository $productRepository,
        protected ProductFlatRepository $productFlatRepository,
    ) {}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        if (request()->ajax()) {
            return datagrid(SellerReviewDataGrid::class)->process();
        }

        return view('marketplace::admin.seller-reviews.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        $review = $this->reviewRepository->find($id);

        if (isset($review)) {
            Event::dispatch('marketplace.seller.review.delete.before', $id);

            $this->reviewRepository->delete($id);

            Event::dispatch('marketplace.seller.review.delete.after', $id);
        }

        return new JsonResponse([
            'message' => trans('marketplace::app.admin.seller-reviews.delete-success'),
        ], 200);
    }

    /**
     * Mass delete the reviews.
     */
    public function massDestroy(MassDestroyRequest $request): JsonResponse
    {
        try {
            foreach ($request->input('indices') as $id) {
                $review = $this->reviewRepository->find($id);

                if (isset($review)) {
                    Event::dispatch('marketplace.seller.review.delete.before', $id);

                    $this->reviewRepository->delete($id);

                    Event::dispatch('marketplace.seller.review.delete.after', $id);
                }
            }

            return new JsonResponse([
                'message' => trans('marketplace::app.admin.seller-reviews.index.datagrid.mass-delete-success'),
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
        foreach ($request->input('indices') as $id) {
            Event::dispatch('marketplace.seller.review.update.before', $id);

            $review = $this->reviewRepository->update([
                'status' => $request->input('value'),
            ], $id);

            Event::dispatch('marketplace.seller.review.update.after', $review);
        }

        return new JsonResponse([
            'message' => trans('marketplace::app.admin.seller-reviews.index.datagrid.mass-update-success'),
        ], 200);
    }
}
