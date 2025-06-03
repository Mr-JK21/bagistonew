<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Marketplace
    |--------------------------------------------------------------------------
    */
    [
        'key'   => 'marketplace',
        'name'  => 'marketplace::app.admin.menu.title',
        'route' => 'admin.marketplace.sellers.index',
        'sort'  => 3,
        'icon'  => 'icon-store',
    ],

    /*
    |--------------------------------------------------------------------------
    | Sellers
    |--------------------------------------------------------------------------
    */
    [
        'key'   => 'marketplace.sellers',
        'name'  => 'marketplace::app.admin.menu.sellers',
        'route' => 'admin.marketplace.sellers.index',
        'sort'  => 1,
        'icon'  => '',
    ],

    /*
    |--------------------------------------------------------------------------
    | Products
    |--------------------------------------------------------------------------
    */
    [
        'key'   => 'marketplace.products',
        'name'  => 'marketplace::app.admin.menu.products',
        'route' => 'admin.marketplace.products.index',
        'sort'  => 2,
        'icon'  => '',
    ],

    /*
    |--------------------------------------------------------------------------
    | Seller Categories
    |--------------------------------------------------------------------------
    */
    [
        'key'   => 'marketplace.seller-categories',
        'name'  => 'marketplace::app.admin.menu.seller-categories',
        'route' => 'admin.marketplace.seller_categories.index',
        'sort'  => 3,
        'icon'  => '',
    ],

    /*
    |--------------------------------------------------------------------------
    | Orders
    |--------------------------------------------------------------------------
    */
    [
        'key'   => 'marketplace.orders',
        'name'  => 'marketplace::app.admin.menu.orders',
        'route' => 'admin.marketplace.orders.index',
        'sort'  => 4,
        'icon'  => '',
    ],

    /*
    |--------------------------------------------------------------------------
    | Payment Requests
    |--------------------------------------------------------------------------
    */
    [
        'key'   => 'marketplace.payment-requests',
        'name'  => 'marketplace::app.admin.menu.payment-requests',
        'route' => 'admin.marketplace.payment_requests.index',
        'sort'  => 5,
        'icon'  => '',
    ],

    /*
    |--------------------------------------------------------------------------
    | Transactions
    |--------------------------------------------------------------------------
    */
    [
        'key'   => 'marketplace.transactions',
        'name'  => 'marketplace::app.admin.menu.transactions',
        'route' => 'admin.marketplace.transactions.index',
        'sort'  => 6,
        'icon'  => '',
    ],

    /*
    |--------------------------------------------------------------------------
    | Product Reviews
    |--------------------------------------------------------------------------
    */
    [
        'key'   => 'marketplace.product-reviews',
        'name'  => 'marketplace::app.admin.menu.product-reviews',
        'route' => 'admin.marketplace.product_reviews.index',
        'sort'  => 7,
        'icon'  => '',
    ],

    /*
    |--------------------------------------------------------------------------
    | Seller Reviews
    |--------------------------------------------------------------------------
    */
    [
        'key'   => 'marketplace.seller-reviews',
        'name'  => 'marketplace::app.admin.menu.seller-reviews',
        'route' => 'admin.marketplace.seller_reviews.index',
        'sort'  => 8,
        'icon'  => '',
    ],

    /*
    |--------------------------------------------------------------------------
    | Flag Reasons
    |--------------------------------------------------------------------------
    */
    [
        'key'   => 'marketplace.flag-reasons',
        'name'  => 'marketplace::app.admin.menu.flag-reasons',
        'route' => 'admin.marketplace.flag_reasons.index',
        'sort'  => 9,
        'icon'  => '',
    ],

    /*
    |--------------------------------------------------------------------------
    | Communications
    |--------------------------------------------------------------------------
    */
    [
        'key'   => 'marketplace.communications',
        'name'  => 'marketplace::app.admin.menu.communications',
        'route' => 'admin.marketplace.communications.index',
        'sort'  => 9,
        'icon'  => '',
    ],
];
