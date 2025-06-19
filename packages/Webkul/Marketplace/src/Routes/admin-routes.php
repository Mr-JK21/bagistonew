<?php

use Illuminate\Support\Facades\Route;
use Webkul\Marketplace\Http\Controllers\Admin\CommunicationController;
use Webkul\Marketplace\Http\Controllers\Admin\FlagReasonController;
use Webkul\Marketplace\Http\Controllers\Admin\OrderController;
use Webkul\Marketplace\Http\Controllers\Admin\PaymentRequestController;
use Webkul\Marketplace\Http\Controllers\Admin\ProductController;
use Webkul\Marketplace\Http\Controllers\Admin\ProductReviewsController;
use Webkul\Marketplace\Http\Controllers\Admin\SellerCategoryController;
use Webkul\Marketplace\Http\Controllers\Admin\SellerController;
use Webkul\Marketplace\Http\Controllers\Admin\SellerReviewController;
use Webkul\Marketplace\Http\Controllers\Admin\TransactionController;

Route::prefix(config('app.admin_url').'/marketplace')
    ->name('admin.marketplace.')
    ->middleware(['admin', 'marketplace'])
    ->group(function () {
        /**
         * ----------------------------------------------------
         * All the sellers routes will be defined here
         * ----------------------------------------------------
         */
        Route::prefix('sellers')
            ->name('sellers.')
            ->controller(SellerController::class)
            ->group(function () {
                Route::get('', 'index')->name('index');

                Route::post('create', 'store')->name('store');

                Route::get('edit/{id}', 'edit')->name('edit');

                Route::put('edit/{id}', 'update')->name('update');

                Route::delete('delete/{id}', 'destroy')->name('delete');

                Route::post('mass-update', 'massUpdate')->name('mass_update');

                Route::post('mass-delete', 'massDestroy')->name('mass_delete');

                Route::get('flags/{id}', 'flags')->name('flags.index');

                // 19th June 2025
                // New GST Verification Route
                Route::get('gst/verify', 'verifyGST')->name('gst.verify');
                // __
                
                Route::prefix('product')
                    ->group(function () {
                        Route::get('search/{id}', 'search')->name('products.search');

                        Route::prefix('assign/{seller_id}/{product_id?}')
                            ->group(function () {
                                Route::get('', 'assignProduct')->name('products.assign');

                                Route::post('', 'saveAssignProduct')->name('products.save_assign');
                            });
                    });
            });

        /**
         * ----------------------------------------------------
         * All the products routes will be defined here
         * ----------------------------------------------------
         */
        Route::prefix('products')
            ->name('products.')
            ->controller(ProductController::class)
            ->group(function () {
                Route::get('', 'index')->name('index');

                Route::delete('delete/{id}', 'destroy')->name('delete');

                Route::post('mass-update', 'massUpdate')->name('mass_update');

                Route::post('mass-delete', 'massDestroy')->name('mass_delete');

                Route::get('flags/{id}', 'flags')->name('flags.index');
            });

        /**
         * ----------------------------------------------------
         * All the seller categories routes will be defined here
         * ----------------------------------------------------
         */
        Route::prefix('seller-categories')
            ->name('seller_categories.')
            ->controller(SellerCategoryController::class)
            ->group(function () {
                Route::get('', 'index')->name('index');

                Route::get('create', 'create')->name('create');

                Route::post('', 'store')->name('store');

                Route::get('edit/{id}', 'edit')->name('edit');

                Route::put('edit/{id}', 'update')->name('update');

                Route::delete('delete/{id}', 'destroy')->name('delete');

                Route::post('mass-update', 'massUpdate')->name('mass_update');

                Route::post('mass-delete', 'massDestroy')->name('mass_delete');
            });

        /**
         * ----------------------------------------------------
         * All the orders routes will be defined here
         * ----------------------------------------------------
         */
        Route::prefix('orders')
            ->name('orders.')
            ->controller(OrderController::class)
            ->group(function () {
                Route::get('', 'index')->name('index');
            });

        /**
         * ----------------------------------------------------
         * All the payment requests routes will be defined here
         * ----------------------------------------------------
         */
        Route::prefix('payment-requests')
            ->name('payment_requests.')
            ->controller(PaymentRequestController::class)
            ->group(function () {
                Route::get('', 'index')->name('index');

                Route::post('pay', 'pay')->name('pay');
            });

        /**
         * ----------------------------------------------------
         * All the transactions routes will be defined here
         * ----------------------------------------------------
         */
        Route::prefix('transactions')
            ->name('transactions.')
            ->controller(TransactionController::class)
            ->group(function () {
                Route::get('', 'index')->name('index');

                Route::get('{id}', 'view')->name('view');
            });

        /**
         * ----------------------------------------------------
         * All the product reviews routes will be defined here
         * ----------------------------------------------------
         */
        Route::prefix('product-reviews')
            ->name('product_reviews.')
            ->controller(ProductReviewsController::class)
            ->group(function () {
                Route::get('', 'index')->name('index');

                Route::put('edit/{id}', 'update')->name('update');

                Route::delete('delete/{id}', 'destroy')->name('delete');

                Route::post('mass-update', 'massUpdate')->name('mass_update');
            });

        /**
         * ----------------------------------------------------
         * All the seller reviews routes will be defined here
         * ----------------------------------------------------
         */
        Route::prefix('seller-reviews')
            ->name('seller_reviews.')
            ->controller(SellerReviewController::class)
            ->group(function () {
                Route::get('', 'index')->name('index');

                Route::delete('delete/{id}', 'destroy')->name('delete');

                Route::post('mass-update', 'massUpdate')->name('mass_update');

                Route::post('mass-delete', 'massDestroy')->name('mass_delete');
            });

        /**
         * ----------------------------------------------------
         * All the flag reasons routes will be defined here
         * ----------------------------------------------------
         */
        Route::prefix('flag-reasons')
            ->name('flag_reasons.')
            ->controller(FlagReasonController::class)
            ->group(function () {
                Route::get('', 'index')->name('index');

                Route::get('create', 'create')->name('create');

                Route::post('', 'store')->name('store');

                Route::get('edit/{id}', 'edit')->name('edit');

                Route::put('edit/{id}', 'update')->name('update');

                Route::delete('delete/{id}', 'destroy')->name('delete');

                Route::post('mass-update', 'massUpdate')->name('mass_update');

                Route::post('mass-delete', 'massDestroy')->name('mass_delete');
            });

        /**
         * ----------------------------------------------------
         * All the communication routes will be defined here
         * ----------------------------------------------------
         */
        Route::prefix('communications')
            ->name('communications.')
            ->controller(CommunicationController::class)
            ->group(function () {
                Route::get('', 'index')->name('index');

                Route::post('send-message/{seller_id}', 'sendMessage')->name('send_message');

                Route::get('messages/{seller_id}', 'messages')->name('messages');

                Route::patch('mark-as-read/{seller_id}', 'markAsRead')->name('mark_as_read');

                Route::delete('clear-chat/{seller_id}', 'clearChat')->name('clear_chat');

                Route::post('handle-action/{id}', 'handleAction')->name('handle_action');
            });
    });
