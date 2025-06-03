<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */
    [
        'key'   => 'dashboard',
        'name'  => 'marketplace::app.seller.menu.dashboard',
        'route' => 'seller.dashboard.index',
        'icon'  => 'mp-dashboard-icon',
        'sort'  => 1,
    ],

    /*
    |--------------------------------------------------------------------------
    | Manage Profile
    |--------------------------------------------------------------------------
    */
    [
        'key'   => 'profile',
        'name'  => 'marketplace::app.seller.menu.manage-profile',
        'route' => 'seller.profile.index',
        'icon'  => 'mp-seller-icon',
        'sort'  => 2,
    ],

    /*
    |--------------------------------------------------------------------------
    | Products
    |--------------------------------------------------------------------------
    */
    [
        'key'   => 'products',
        'name'  => 'marketplace::app.seller.menu.products',
        'route' => 'seller.products.index',
        'icon'  => 'mp-products-icon',
        'sort'  => 3,
    ],

    /*
    |--------------------------------------------------------------------------
    | Product Reviews
    |--------------------------------------------------------------------------
    */
    [
        'key'   => 'product_reviews',
        'name'  => 'marketplace::app.seller.menu.product-reviews',
        'route' => 'seller.product_reviews.index',
        'icon'  => 'mp-product-reviews-icon',
        'sort'  => 4,
    ],

    /*
    |--------------------------------------------------------------------------
    | Orders
    |--------------------------------------------------------------------------
    */
    [
        'key'   => 'orders',
        'name'  => 'marketplace::app.seller.menu.orders',
        'route' => 'seller.orders.index',
        'icon'  => 'mp-orders-icon',
        'sort'  => 5,
    ],

    /*
    |--------------------------------------------------------------------------
    | Transactions
    |--------------------------------------------------------------------------
    */
    [
        'key'   => 'transactions',
        'name'  => 'marketplace::app.seller.menu.transaction',
        'route' => 'seller.transactions.index',
        'icon'  => 'mp-transaction-icon',
        'sort'  => 6,
    ],

    /*
    |--------------------------------------------------------------------------
    | Customers
    |--------------------------------------------------------------------------
    */
    [
        'key'   => 'customers',
        'name'  => 'marketplace::app.seller.menu.customers',
        'route' => 'seller.customers.index',
        'icon'  => 'mp-customers-icon',
        'sort'  => 7,
    ],

    /*
    |--------------------------------------------------------------------------
    | Seller Reviews
    |--------------------------------------------------------------------------
    */
    [
        'key'   => 'seller_reviews',
        'name'  => 'marketplace::app.seller.menu.seller-reviews',
        'route' => 'seller.seller_reviews.index',
        'icon'  => 'mp-seller-reviews-icon',
        'sort'  => 8,
    ],

    /*
    |--------------------------------------------------------------------------
    | Settings
    |--------------------------------------------------------------------------
    */
    [
        'key'   => 'settings',
        'name'  => 'marketplace::app.seller.menu.settings',
        'route' => 'seller.settings.general.index',
        'icon'  => 'mp-setting-icon',
        'sort'  => 9,
    ], [
        'key'   => 'settings.general',
        'name'  => 'marketplace::app.seller.menu.general',
        'route' => 'seller.settings.general.index',
        'icon'  => 'mp-setting-icon',
        'sort'  => 1,
    ], [
        'key'   => 'settings.users',
        'name'  => 'marketplace::app.seller.menu.users',
        'route' => 'seller.settings.users.index',
        'icon'  => 'mp-user-icon',
        'sort'  => 2,
    ], [
        'key'   => 'settings.roles',
        'name'  => 'marketplace::app.seller.menu.roles',
        'route' => 'seller.settings.roles.index',
        'icon'  => 'mp-eye-icon',
        'sort'  => 3,
    ],

    /*
    |--------------------------------------------------------------------------
    | Communication
    |--------------------------------------------------------------------------
    */
    [
        'key'   => 'communication',
        'name'  => 'marketplace::app.seller.menu.communication',
        'route' => 'seller.communication.index',
        'icon'  => 'mp-communication-icon',
        'sort'  => 10,
    ],

    /*
    |--------------------------------------------------------------------------
    | Reporting
    |--------------------------------------------------------------------------
    */
    [
        'key'   => 'reporting',
        'name'  => 'marketplace::app.seller.menu.reporting',
        'route' => 'seller.reporting.products.index',
        'icon'  => 'mp-report-icon',
        'sort'  => 11,
    ], [
        'key'   => 'reporting.sales',
        'name'  => 'marketplace::app.seller.menu.sales',
        'route' => 'seller.reporting.sales.index',
        'icon'  => 'mp-sales-icon',
        'sort'  => 1,
    ], [
        'key'   => 'reporting.customers',
        'name'  => 'marketplace::app.seller.menu.customers',
        'route' => 'seller.reporting.customers.index',
        'icon'  => 'mp-customers-icon',
        'sort'  => 2,
    ], [
        'key'   => 'reporting.products',
        'name'  => 'marketplace::app.seller.menu.products',
        'route' => 'seller.reporting.products.index',
        'icon'  => 'mp-products-icon',
        'sort'  => 3,
    ],
];
