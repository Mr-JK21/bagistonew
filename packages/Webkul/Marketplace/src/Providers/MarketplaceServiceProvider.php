<?php

namespace Webkul\Marketplace\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Webkul\Checkout\Contracts\CartItem as BaseCartItemContract;
use Webkul\Core\Contracts\Visit as VisitContract;
use Webkul\Marketplace\Cart;
use Webkul\Marketplace\Console\Commands\InstallMarketplace;
use Webkul\Marketplace\Console\Commands\MarketplaceFaker;
use Webkul\Marketplace\Console\Commands\MarketplaceVersion;
use Webkul\Marketplace\Helpers\Indexers\Inventory;
use Webkul\Marketplace\Http\Middleware\Locale;
use Webkul\Marketplace\Http\Middleware\Marketplace;
use Webkul\Marketplace\Http\Middleware\Seller;
use Webkul\Marketplace\Models\CartItem;
use Webkul\Marketplace\Models\Catalog\Product;
use Webkul\Marketplace\Models\Core\Visit;
use Webkul\Marketplace\Models\ProductOrderedInventory;
use Webkul\Marketplace\Observers\ProductReviewObserver;
use Webkul\Marketplace\ProductTypes\Downloadable as DownloadableProductType;
use Webkul\Marketplace\ProductTypes\Grouped as GroupedProductType;
use Webkul\Marketplace\ProductTypes\Simple as SimpleProductType;
use Webkul\Marketplace\Repositories\BaseProductRepository as BaseMpProductRepository;
use Webkul\Marketplace\Repositories\Sales\OrderItemRepository;
use Webkul\Marketplace\Repositories\Sales\RefundItemRepository;
use Webkul\Marketplace\Repositories\Sales\ShipmentItemRepository;
use Webkul\Marketplace\Repositories\Sales\ShipmentRepository;
use Webkul\Product\Contracts\Product as ProductContract;
use Webkul\Product\Contracts\ProductOrderedInventory as ProductOrderedInventoryContract;
use Webkul\Product\Helpers\Indexers\Inventory as BaseInventory;
use Webkul\Product\Models\ProductReview;
use Webkul\Product\Repositories\ProductRepository as BaseProductRepository;
use Webkul\Product\Type\Downloadable as BaseDownloadableProductType;
use Webkul\Product\Type\Grouped as BaseGroupedProductType;
use Webkul\Product\Type\Simple as BaseSimpleProductType;
use Webkul\Sales\Repositories\OrderItemRepository as BaseOrderItemRepository;
use Webkul\Sales\Repositories\RefundItemRepository as BaseRefundItemRepository;
use Webkul\Sales\Repositories\ShipmentItemRepository as BaseShipmentItemRepository;
use Webkul\Sales\Repositories\ShipmentRepository as BaseShipmentRepository;

class MarketplaceServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            dirname(__DIR__).'/Config/admin/system.php',
            'core'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__).'/Config/admin/acl.php',
            'acl'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__).'/Config/seller/acl.php',
            'marketplace_acl'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__).'/Config/seller/menu.php',
            'marketplace_menu.seller'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__).'/Config/bagisto-vite.php',
            'bagisto-vite.viters'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__).'/Config/response-cache.php',
            'responsecache.replacers'
        );

        $this->mergeAuthConfigs();
    }

    /**
     * Merge Auth Configs.
     *
     * @return void
     */
    public function mergeAuthConfigs()
    {
        foreach (['guards', 'providers', 'passwords'] as $key) {
            $this->mergeConfigFrom(
                dirname(__DIR__).'/Config/seller/auth/'.$key.'.php',
                'auth.'.$key
            );
        }
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        include __DIR__.'/../Http/helpers.php';

        $router->aliasMiddleware('seller', Seller::class);

        $router->aliasMiddleware('seller.locale', Locale::class);

        $router->aliasMiddleware('marketplace', Marketplace::class);

        Route::middleware('web')->group(__DIR__.'/../Routes/web.php');

        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');

        $this->loadRoutesFrom(__DIR__.'/../Routes/breadcrumbs.php');

        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'marketplace');

        Blade::anonymousComponentPath(__DIR__.'/../Resources/views/components', 'marketplace');

        $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'marketplace');

        $this->registerCommands();

        $this->registerProviders();

        $this->resisterObservers();

        $this->overrideCoreModels();

        $this->bindClasses();

        $this->publishAssets();

        /**
         * Checks if the `core_config` table exists.
         * This is necessary because if someone installs Bagisto and the marketplace module simultaneously,
         * it may cause an error due to the `core_config` table not being available during the installation process.
         */
        if (
            Schema::hasTable('core_config')
            && core()->getConfigData('marketplace.settings.general.status')
        ) {
            $this->mergeConfigFrom(
                dirname(__DIR__).'/Config/admin/menu.php',
                'menu.admin'
            );
        }
    }

    /**
     * Register the commands.
     */
    protected function registerCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallMarketplace::class,
                MarketplaceFaker::class,
                MarketplaceVersion::class,
            ]);
        }
    }

    /**
     * Register the providers.
     */
    protected function registerProviders(): void
    {
        $this->app->register(ModuleServiceProvider::class);

        $this->app->register(EventServiceProvider::class);
    }

    /**
     * Register the observers.
     */
    protected function resisterObservers(): void
    {
        ProductReview::observe(ProductReviewObserver::class);
    }

    /**
     * Override the core models.
     */
    protected function overrideCoreModels(): void
    {
        $this->app->concord->registerModel(VisitContract::class, Visit::class);

        $this->app->concord->registerModel(ProductContract::class, Product::class);

        $this->app->concord->registerModel(BaseCartItemContract::class, CartItem::class);

        $this->app->concord->registerModel(ProductOrderedInventoryContract::class, ProductOrderedInventory::class);
    }

    /**
     * Bind the classes.
     */
    protected function bindClasses(): void
    {
        $this->app->bind('cart', Cart::class);

        $this->app->bind(BaseProductRepository::class, BaseMpProductRepository::class);

        $this->app->bind(BaseInventory::class, Inventory::class);

        $this->app->bind(BaseOrderItemRepository::class, OrderItemRepository::class);

        $this->app->bind(BaseShipmentRepository::class, ShipmentRepository::class);

        $this->app->bind(BaseShipmentItemRepository::class, ShipmentItemRepository::class);

        $this->app->bind(BaseRefundItemRepository::class, RefundItemRepository::class);

        $this->app->bind(BaseSimpleProductType::class, SimpleProductType::class);

        $this->app->bind(BaseDownloadableProductType::class, DownloadableProductType::class);

        $this->app->bind(BaseGroupedProductType::class, GroupedProductType::class);
    }

    /**
     * Publish the assets.
     */
    protected function publishAssets(): void
    {
        $this->publishes([
            __DIR__.'/../../publishable/storage' => storage_path('app/public'),
        ]);

        $this->publishes([
            __DIR__.'/../../publishable/build' => public_path('themes/marketplace/build'),
        ], 'public');

        $this->publishes([
            __DIR__.'/../Resources/views/components/dropdown/index.blade.php' => resource_path('themes/default/views/components/dropdown/index.blade.php'),

            __DIR__.'/../Resources/views/components/datagrid/index.blade.php' => resource_path('themes/default/views/components/datagrid/index.blade.php'),
        ]);
    }
}
