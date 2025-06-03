<?php

namespace Webkul\Marketplace\Http\Controllers\Shop;

use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Webkul\Category\Repositories\CategoryRepository;
use Webkul\Marketplace\Repositories\SellerRepository;
use Webkul\Shop\Http\Controllers\Controller;

class MarketplaceController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected CategoryRepository $categoryRepository,
        protected SellerRepository $sellerRepository
    ) {}

    /**
     * Display the specified resource.
     */
    public function index(): View
    {
        return view('marketplace::shop.marketplace.index');
    }

    /**
     * Returns the list of popular sellers.
     */
    public function getFeaturedSellers(): JsonResponse
    {
        $sellers = $this->sellerRepository
            ->with('category')
            ->getFeaturedSellers()
            ->map(function ($seller) {
                $categoryIds = $seller->category->categories ?? [];

                $seller->allowed_categories = $this->categoryRepository
                    ->findWhereIn('id', $categoryIds)
                    ->pluck('name');

                return $seller;
            });

        return new JsonResponse($sellers);
    }
}
