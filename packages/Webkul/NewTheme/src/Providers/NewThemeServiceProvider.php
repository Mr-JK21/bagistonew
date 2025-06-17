<?php

namespace Webkul\NewTheme\Providers;

use Illuminate\Support\ServiceProvider;

class NewThemeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../Resources/views' => resource_path('themes/new-theme/views'),
        ]);
    }
}
