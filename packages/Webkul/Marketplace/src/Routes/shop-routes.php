<?php

use Illuminate\Support\Facades\Route;
use Webkul\Marketplace\Http\Controllers\Shop\CartController;
use Webkul\Marketplace\Http\Controllers\Shop\MarketplaceController;
use Webkul\Marketplace\Http\Controllers\Shop\ProductController;
use Webkul\Marketplace\Http\Controllers\Shop\ProductFlagController;
use Webkul\Marketplace\Http\Controllers\Shop\ProductsCategoriesProxyController;
use Webkul\Marketplace\Http\Controllers\Shop\ReviewController;
use Webkul\Marketplace\Http\Controllers\Shop\SellerController;

Route::middleware(['theme', 'locale', 'currency', 'marketplace'])->group(function () {
    Route::prefix('marketplace')
        ->name('shop.marketplace.')
        ->group(function () {
            /**
             * ----------------------------------------------------------------
             * Marketplace seller central routes.
             * ----------------------------------------------------------------
             */
            Route::controller(MarketplaceController::class)
                ->group(function () {
                    Route::get('', 'index')->name('index')->middleware('cacheResponse');

                    Route::get('featured-sellers', 'getFeaturedSellers')->name('featured_sellers');
                });

            /**
             * ----------------------------------------------------------------
             * Marketplace add to cart routes.
             * ----------------------------------------------------------------
             */
            Route::prefix('checkout/cart')
                ->name('checkout.cart.')
                ->controller(CartController::class)
                ->group(function () {
                    Route::post('add', 'store')->name('store');
                });

            /**
             * ----------------------------------------------------------------
             * Marketplace product related routes.
             * ----------------------------------------------------------------
             */
            Route::prefix('products')
                ->name('products.')
                ->controller(ProductController::class)
                ->group(function () {
                    Route::get('offers/{id}', 'offers')->name('offers.index');

                    Route::get('download-sample/{type}/{id}', 'downloadSample')->name('offers.download_sample');

                    Route::get('top-selling/{sellerId}', 'topSellingProducts')->name('seller.top_selling');
                });

            /**
             * ----------------------------------------------------------------
             * Seller related routes.
             * ----------------------------------------------------------------
             */
            Route::name('sellers.')
                ->controller(SellerController::class)
                ->group(function () {
                    Route::get('{url}', 'show')->name('show');

                    Route::post('contact/{url}', 'contact')->name('contact');

                    Route::post('flag/{url}', 'flag')->name('flag');

                    Route::get('products/{url}', 'products')->name('products');
                });

            /**
             * ----------------------------------------------------------------
             * Product flag routes.
             * ----------------------------------------------------------------
             */
            Route::prefix('products/flag')
                ->name('products.flag.')
                ->controller(ProductFlagController::class)
                ->group(function () {
                    Route::post('store', 'store')->name('store');
                });

            /**
             * ----------------------------------------------------------------
             * Review routes.
             * ----------------------------------------------------------------
             */
            Route::prefix('reviews')
                ->name('seller.reviews.')
                ->controller(ReviewController::class)
                ->group(function () {
                    Route::get('{url}', 'index')->name('index');

                    Route::post('create', 'store')->name('store');
                });
        });

    /**
     * ----------------------------------------------------------------
     * Fallback route (overrides the one in Shop package).
     * ----------------------------------------------------------------
     */
    Route::fallback(ProductsCategoriesProxyController::class.'@index')
        ->name('shop.product_or_category.index')
        ->middleware('cacheResponse');
});
