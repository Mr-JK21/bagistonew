<?php

use Illuminate\Support\Facades\Route;
use Webkul\Marketplace\Http\Controllers\Seller\Controller;
use Webkul\Marketplace\Http\Controllers\Seller\ForgotPasswordController;
use Webkul\Marketplace\Http\Controllers\Seller\RegistrationController;
use Webkul\Marketplace\Http\Controllers\Seller\ResetPasswordController;
use Webkul\Marketplace\Http\Controllers\Seller\SessionController;

Route::prefix('seller')
    ->name('seller.')
    ->middleware(['theme', 'marketplace'])
    ->group(function () {
        /**
         * ----------------------------------------
         * Redirect route.
         * ----------------------------------------
         */
        Route::get('', [Controller::class, 'redirectToLogin']);

        /**
         * ----------------------------------------
         * Session routes.
         * ----------------------------------------
         */
        Route::prefix('login')
            ->name('session.')
            ->controller(SessionController::class)
            ->group(function () {
                Route::get('', 'show')->name('index');

                Route::post('', 'create')->name('create');
            });

        /**
         * ----------------------------------------
         * Registration routes.
         * ----------------------------------------
         */
        Route::prefix('register')
            ->name('register.')
            ->controller(RegistrationController::class)
            ->group(function () {
                Route::get('', 'index')->name('create');

                Route::post('', 'store')->name('store');
            });

        /**
         * ----------------------------------------
         * Forgot password routes.
         * ----------------------------------------
         */
        Route::prefix('forgot-password')
            ->name('forgot_password.')
            ->controller(ForgotPasswordController::class)
            ->group(function () {
                Route::get('', 'create')->name('create');

                Route::post('', 'store')->name('store');
            });

        /**
         * ----------------------------------------
         * Reset password routes.
         * ----------------------------------------
         */
        Route::prefix('reset-password')
            ->name('reset_password.')
            ->controller(ResetPasswordController::class)
            ->group(function () {
                Route::get('{token}', 'create')->name('create');

                Route::post('', 'store')->name('store');
            });
    });
