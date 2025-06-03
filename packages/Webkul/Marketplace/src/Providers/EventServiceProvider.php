<?php

namespace Webkul\Marketplace\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Schema;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'marketplace.seller.create.after' => [
            'Webkul\Marketplace\Listeners\Seller@afterCreate',
        ],

        'marketplace.seller.update.after' => [
            'Webkul\Marketplace\Listeners\Seller@afterUpdate',
        ],

        'catalog.product.update.after' => [
            'Webkul\Marketplace\Listeners\Product@afterUpdate',
        ],

        'marketplace.product.update.after' => [
            'Webkul\Marketplace\Listeners\Product@afterSellerProductUpdate',
        ],

        'marketplace.assign-product.create.after' => [
            'Webkul\Marketplace\Listeners\Product@afterAssignProductUpdateOrCreate',
        ],

        'marketplace.assign-product.update.after' => [
            'Webkul\Marketplace\Listeners\Product@afterAssignProductUpdateOrCreate',
        ],

        'checkout.cart.collect.totals.before' => [
            'Webkul\Marketplace\Listeners\Cart@collectTotalsBefore',
        ],

        'checkout.order.save.after' => [
            'Webkul\Marketplace\Listeners\Order@afterPlaceOrder',
        ],

        'sales.order.cancel.after' => [
            'Webkul\Marketplace\Listeners\Order@afterOrderCancel',
        ],

        'marketplace.sales.order.save.after' => [
            'Webkul\Marketplace\Listeners\Order@sendNewOrderMail',
        ],

        'sales.invoice.save.after' => [
            'Webkul\Marketplace\Listeners\Invoice@afterInvoice',
        ],

        'sales.shipment.save.after' => [
            'Webkul\Marketplace\Listeners\Shipment@afterShipment',
        ],

        'sales.refund.save.after' => [
            'Webkul\Marketplace\Listeners\Refund@afterRefund',
        ],

        'core.configuration.save.after' => [
            'Webkul\Marketplace\Listeners\Configuration@afterUpdate',
        ],
    ];

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $eventTemplates = [
            [
                'event'    => 'bagisto.shop.layout.head.before',
                'template' => 'marketplace::components.shop.layouts.header.style',
            ], [
                'event'    => 'bagisto.admin.layout.head.before',
                'template' => 'marketplace::components.admin.layouts.header.style',
            ], [
                'event'    => 'bagisto.shop.products.view.additional_actions.after',
                'template' => 'marketplace::shop.products.product-sellers',
            ], [
                'event'    => 'bagisto.shop.products.view.after',
                'template' => 'marketplace::shop.products.top-selling',
            ], [
                'event'    => 'bagisto.shop.components.layouts.header.desktop.bottom.mini_cart.after',
                'template' => 'marketplace::components.shop.layouts.header.sell',
            ], [
                'event'    => 'bagisto.shop.components.layouts.header.mobile.mini_cart.after',
                'template' => 'marketplace::components.shop.layouts.header.sell',
            ], [
                'event'    => 'bagisto.admin.catalog.product.edit.form.after',
                'template' => 'marketplace::admin.products.edit.vendor-id',
            ], [
                'event'    => 'bagisto.shop.components.layouts.header.desktop.bottom.profile_dropdown.links.before',
                'template' => 'marketplace::components.shop.layouts.header.profile',
            ], [
                'event'    => 'bagisto.shop.components.layouts.header.desktop.bottom.customers_action.before',
                'template' => 'marketplace::components.shop.layouts.header.profile',
            ], [
                'event'    => 'bagisto.shop.components.layouts.header.mobile.drawer.categories.before',
                'template' => 'marketplace::components.shop.layouts.header.profile',
            ], [
                'event'    => 'bagisto.shop.checkout.cart.breadcrumbs.after',
                'template' => 'marketplace::shop.checkout.cart.error',
            ],
        ];

        /**
         * Checks if the `core_config` table exists.
         * This is necessary because if someone installs Bagisto and the marketplace module simultaneously,
         * it may cause an error due to the `core_config` table not being available during the installation process.
         */
        if (
            Schema::hasTable('core_config')
            && core()->getConfigData('marketplace.settings.general.status')
        ) {
            foreach ($eventTemplates as $eventTemplate) {
                Event::listen(current($eventTemplate), fn ($e) => $e->addTemplate(end($eventTemplate)));
            }
        }
    }
}
