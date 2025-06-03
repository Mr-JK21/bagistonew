<?php

use Illuminate\Support\Facades\Route;
use Webkul\Marketplace\Http\Controllers\Seller\CommunicationController;
use Webkul\Marketplace\Http\Controllers\Seller\CustomerController;
use Webkul\Marketplace\Http\Controllers\Seller\DashboardController;
use Webkul\Marketplace\Http\Controllers\Seller\MegaSearchController;
use Webkul\Marketplace\Http\Controllers\Seller\Orders\InvoiceController;
use Webkul\Marketplace\Http\Controllers\Seller\Orders\OrderController;
use Webkul\Marketplace\Http\Controllers\Seller\Orders\PaymentRequestController;
use Webkul\Marketplace\Http\Controllers\Seller\Orders\ShipmentController;
use Webkul\Marketplace\Http\Controllers\Seller\ProductReviewController;
use Webkul\Marketplace\Http\Controllers\Seller\Products\AssignProductController;
use Webkul\Marketplace\Http\Controllers\Seller\Products\ProductController;
use Webkul\Marketplace\Http\Controllers\Seller\ProfileController;
use Webkul\Marketplace\Http\Controllers\Seller\Reporting;
use Webkul\Marketplace\Http\Controllers\Seller\SellerReviewController;
use Webkul\Marketplace\Http\Controllers\Seller\SessionController;
use Webkul\Marketplace\Http\Controllers\Seller\Settings\GeneralController;
use Webkul\Marketplace\Http\Controllers\Seller\Settings\RoleController;
use Webkul\Marketplace\Http\Controllers\Seller\Settings\UserController;
use Webkul\Marketplace\Http\Controllers\Seller\TransactionController;

Route::prefix('seller')
    ->name('seller.')
    ->middleware(['theme', 'marketplace', 'seller', 'seller.locale'])
    ->group(function () {
        /**
         * ----------------------------------------------
         * All the session routes will be defined here
         * ----------------------------------------------
         */
        Route::prefix('logout')
            ->name('session.')
            ->controller(SessionController::class)
            ->group(function () {
                Route::delete('', 'destroy')->name('destroy');
            });

        /**
         * ----------------------------------------------
         * All the mega search routes will be defined here
         * ----------------------------------------------
         */
        Route::prefix('mega-search')
            ->name('mega_search.')
            ->controller(MegaSearchController::class)
            ->group(function () {
                Route::get('products', 'products')->name('products');

                Route::get('orders', 'orders')->name('orders');

                Route::get('customers', 'customers')->name('customers');
            });

        /**
         * ----------------------------------------------
         * All the dashboard routes will be defined here
         * ----------------------------------------------
         */
        Route::prefix('dashboard')
            ->name('dashboard.')
            ->controller(DashboardController::class)
            ->group(function () {
                Route::get('', 'index')->name('index');

                Route::get('stats', 'stats')->name('stats');
            });

        /**
         * ----------------------------------------------
         * All the profile routes will be defined here
         * ----------------------------------------------
         */
        Route::prefix('profile')
            ->name('profile.')
            ->controller(ProfileController::class)
            ->group(function () {
                Route::get('', 'index')->name('index');

                Route::put('{id}', 'update')->name('update');
            });

        /**
         * ----------------------------------------------
         * All the product routes will be defined here
         * ----------------------------------------------
         */
        Route::prefix('products')->group(function () {
            Route::name('products.')
                ->controller(ProductController::class)
                ->group(function () {
                    Route::get('', 'index')->name('index');

                    Route::get('create', 'create')->name('create');

                    Route::get('search', 'search')->name('search');

                    Route::get('search-simple', 'searchSimple')->name('search_simple');

                    Route::post('create', 'store')->name('store');

                    Route::get('edit/{id}', 'edit')->name('edit');

                    Route::put('edit/{id}', 'update')->name('update');

                    Route::delete('edit/{id}', 'destroy')->name('delete');

                    Route::post('mass-delete', 'massDestroy')->name('mass_delete');

                    Route::post('upload-file/{id}', 'uploadLink')->name('upload_link');

                    Route::post('upload-sample/{id}', 'uploadSample')->name('upload_sample');
                });

            Route::prefix('assign')
                ->name('products.assign.')
                ->controller(AssignProductController::class)
                ->group(function () {
                    Route::get('{id?}', 'create')->name('create');

                    Route::get('edit/{id}', 'edit')->name('edit');

                    Route::post('{id?}', 'store')->name('store');

                    Route::put('{id}', 'update')->name('update');

                    Route::post('upload-file/{id}', 'uploadLink')->name('upload_link');

                    Route::post('upload-sample/{id}', 'uploadSample')->name('upload_sample');
                });
        });

        /**
         * ----------------------------------------------
         * All the product review routes will be defined here
         * ----------------------------------------------
         */
        Route::prefix('product-reviews')
            ->name('product_reviews.')
            ->controller(ProductReviewController::class)
            ->group(function () {
                Route::get('', 'index')->name('index');

                Route::post('mass-update', 'massUpdate')->name('mass_update');
            });

        /**
         * ----------------------------------------------
         * All the order routes will be defined here
         * ----------------------------------------------
         */
        Route::prefix('orders')->group(function () {
            Route::name('orders.')
                ->controller(OrderController::class)
                ->group(function () {
                    Route::get('', 'index')->name('index');

                    Route::get('view/{order_id}', 'view')->name('view');

                    Route::get('cancel/{order_id}', 'cancel')->name('cancel');
                });

            Route::prefix('invoices')
                ->name('invoices.')
                ->controller(InvoiceController::class)
                ->group(function () {
                    Route::post('create/{id}', 'store')->name('store');

                    Route::get('print/{id}', 'print')->name('print');
                });

            Route::prefix('shipments')
                ->name('shipments.')
                ->controller(ShipmentController::class)
                ->group(function () {
                    Route::post('create/{id}', 'store')->name('store');
                });

            Route::prefix('payment-request')
                ->name('payment.')
                ->controller(PaymentRequestController::class)
                ->group(function () {
                    Route::get('{id}', 'requestPayment')->name('request');
                });
        });

        /**
         * ----------------------------------------------
         * All the transaction routes will be defined here
         * ----------------------------------------------
         */
        Route::prefix('transactions')
            ->name('transactions.')
            ->controller(TransactionController::class)
            ->group(function () {
                Route::get('', 'index')->name('index');

                Route::get('view/{id}', 'view')->name('view');

                Route::get('print/{id}', 'print')->name('print');
            });

        /**
         * ----------------------------------------------
         * All the customer routes will be defined here
         * ----------------------------------------------
         */
        Route::prefix('customers')
            ->name('customers.')
            ->controller(CustomerController::class)
            ->group(function () {
                Route::get('', 'index')->name('index');
            });

        /**
         * ----------------------------------------------
         * All the seller review routes will be defined here
         * ----------------------------------------------
         */
        Route::prefix('seller-reviews')
            ->name('seller_reviews.')
            ->controller(SellerReviewController::class)
            ->group(function () {
                Route::get('', 'index')->name('index');
            });

        /**
         * ----------------------------------------------
         * All the settings routes will be defined here
         * ----------------------------------------------
         */
        Route::prefix('settings')->group(function () {
            Route::prefix('general')
                ->name('settings.general.')
                ->controller(GeneralController::class)
                ->group(function () {
                    Route::get('', 'index')->name('index');

                    Route::put('', 'update')->name('update');
                });

            Route::prefix('users')
                ->name('settings.users.')
                ->controller(UserController::class)
                ->group(function () {
                    Route::get('', 'index')->name('index');

                    Route::post('', 'store')->name('store');

                    Route::get('edit/{id}', 'edit')->name('edit');

                    Route::put('edit', 'update')->name('update');

                    Route::delete('edit/{id}', 'destroy')->name('delete');
                });

            /**
             * ----------------------------------------------
             * All the role routes will be defined here
             * ----------------------------------------------
             */
            Route::prefix('roles')
                ->name('settings.roles.')
                ->controller(RoleController::class)
                ->group(function () {
                    Route::get('', 'index')->name('index');

                    Route::get('create', 'create')->name('create');

                    Route::post('create', 'store')->name('store');

                    Route::get('edit/{id}', 'edit')->name('edit');

                    Route::put('edit/{id}', 'update')->name('update');

                    Route::delete('edit/{id}', 'destroy')->name('delete');
                });
        });

        /**
         * ----------------------------------------------
         * All the communication routes will be defined here
         * ----------------------------------------------
         */
        Route::prefix('communication')
            ->name('communication.')
            ->controller(CommunicationController::class)
            ->group(function () {
                Route::get('', 'index')->name('index');

                Route::post('send-message', 'sendMessage')->name('send_message');

                Route::get('messages', 'messages')->name('messages');
            });

        /**
         * ----------------------------------------------
         * All the reporting routes will be defined here
         * ----------------------------------------------
         */
        Route::prefix('reporting')->group(function () {
            Route::prefix('customers')
                ->name('reporting.customers.')
                ->controller(Reporting\CustomerController::class)
                ->group(function () {
                    Route::get('', 'index')->name('index');

                    Route::get('stats', 'stats')->name('stats');

                    Route::get('export', 'export')->name('export');

                    Route::get('view', 'view')->name('view');

                    Route::get('view/stats', 'viewStats')->name('view.stats');
                });

            Route::prefix('products')
                ->name('reporting.products.')
                ->controller(Reporting\ProductController::class)
                ->group(function () {
                    Route::get('', 'index')->name('index');

                    Route::get('stats', 'stats')->name('stats');

                    Route::get('export', 'export')->name('export');

                    Route::get('view', 'view')->name('view');

                    Route::get('view/stats', 'viewStats')->name('view.stats');
                });

            Route::prefix('sales')
                ->name('reporting.sales.')
                ->controller(Reporting\SaleController::class)
                ->group(function () {
                    Route::get('', 'index')->name('index');

                    Route::get('stats', 'stats')->name('stats');

                    Route::get('export', 'export')->name('export');

                    Route::get('view', 'view')->name('view');

                    Route::get('view/stats', 'viewStats')->name('view.stats');
                });
        });
    });
