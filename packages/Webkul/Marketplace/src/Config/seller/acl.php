<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    |
    | All ACLs related to dashboard will be placed here.
    |
    */
    [
        'key'   => 'dashboard',
        'name'  => 'marketplace::app.seller.acl.dashboard',
        'route' => 'seller.dashboard.index',
        'sort'  => 1,
    ],

    /*
    |--------------------------------------------------------------------------
    | Products
    |--------------------------------------------------------------------------
    |
    | All ACLs related to products will be placed here.
    |
    */
    [
        'key'   => 'products',
        'name'  => 'marketplace::app.seller.acl.products',
        'route' => 'seller.products.index',
        'sort'  => 2,
    ], [
        'key'   => 'products.create',
        'name'  => 'marketplace::app.seller.acl.create',
        'route' => 'seller.products.create',
        'sort'  => 1,
    ], [
        'key'   => 'products.assign',
        'name'  => 'marketplace::app.seller.acl.assign',
        'route' => 'seller.products.assign.create',
        'sort'  => 2,
    ], [
        'key'   => 'products.edit',
        'name'  => 'marketplace::app.seller.acl.edit',
        'route' => 'seller.products.edit',
        'sort'  => 3,
    ], [
        'key'   => 'products.delete',
        'name'  => 'marketplace::app.seller.acl.delete',
        'route' => 'seller.products.delete',
        'sort'  => 4,
    ],

    /*
    |--------------------------------------------------------------------------
    | Product Reviews
    |--------------------------------------------------------------------------
    |
    | All ACLs related to reviews will be placed here.
    |
    */
    [
        'key'   => 'product_reviews',
        'name'  => 'marketplace::app.seller.acl.product-reviews',
        'route' => 'seller.product_reviews.index',
        'sort'  => 3,
    ], [
        'key'   => 'product_reviews.edit',
        'name'  => 'marketplace::app.seller.acl.edit',
        'route' => 'seller.product_reviews.mass_update',
        'sort'  => 1,
    ],

    /*
    |--------------------------------------------------------------------------
    | Orders
    |--------------------------------------------------------------------------
    |
    | All ACLs related to orders will be placed here.
    |
    */
    [
        'key'   => 'orders',
        'name'  => 'marketplace::app.seller.acl.orders',
        'route' => 'seller.orders.index',
        'sort'  => 4,
    ], [
        'key'   => 'orders.view',
        'name'  => 'marketplace::app.seller.acl.view',
        'route' => 'seller.orders.view',
        'sort'  => 1,
    ], [
        'key'   => 'orders.cancel',
        'name'  => 'marketplace::app.seller.acl.cancel',
        'route' => 'seller.orders.cancel',
        'sort'  => 2,
    ], [
        'key'   => 'orders.invoice',
        'name'  => 'marketplace::app.seller.acl.invoice',
        'route' => 'seller.invoices.store',
        'sort'  => 3,
    ], [
        'key'   => 'orders.print_invoice',
        'name'  => 'marketplace::app.seller.acl.print-invoice',
        'route' => 'seller.invoices.print',
        'sort'  => 4,
    ], [
        'key'   => 'orders.shipment',
        'name'  => 'marketplace::app.seller.acl.shipment',
        'route' => 'seller.shipments.store',
        'sort'  => 5,
    ], [
        'key'   => 'orders.payment_request',
        'name'  => 'marketplace::app.seller.acl.payment-request',
        'route' => 'seller.payment.request',
        'sort'  => 6,
    ],

    /*
    |--------------------------------------------------------------------------
    | Transactions
    |--------------------------------------------------------------------------
    |
    | All ACLs related to transactions will be placed here.
    |
    */
    [
        'key'   => 'transactions',
        'name'  => 'marketplace::app.seller.acl.transactions',
        'route' => 'seller.transactions.index',
        'sort'  => 5,
    ], [
        'key'   => 'transactions.view',
        'name'  => 'marketplace::app.seller.acl.view',
        'route' => 'seller.transactions.view',
        'sort'  => 1,
    ], [
        'key'   => 'transactions.print',
        'name'  => 'marketplace::app.seller.acl.print',
        'route' => 'seller.transactions.print',
        'sort'  => 2,
    ],

    /*
    |--------------------------------------------------------------------------
    | Customers
    |--------------------------------------------------------------------------
    |
    | All ACLs related to customers will be placed here.
    |
    */
    [
        'key'   => 'customers',
        'name'  => 'marketplace::app.seller.acl.customers',
        'route' => 'seller.customers.index',
        'sort'  => 6,
    ],

    /*
    |--------------------------------------------------------------------------
    | Seller Reviews
    |--------------------------------------------------------------------------
    |
    | All ACLs related to seller reviews will be placed here.
    |
    */
    [
        'key'   => 'seller_reviews',
        'name'  => 'marketplace::app.seller.acl.seller-reviews',
        'route' => 'seller.seller_reviews.index',
        'sort'  => 7,
    ],

    /*
    |--------------------------------------------------------------------------
    | Settings
    |--------------------------------------------------------------------------
    |
    | All ACLs related to settings will be placed here.
    |
    */
    [
        'key'   => 'settings',
        'name'  => 'marketplace::app.seller.acl.settings',
        'route' => 'seller.settings.general.index',
        'sort'  => 8,
    ], [
        'key'   => 'settings.general',
        'name'  => 'marketplace::app.seller.acl.general',
        'route' => 'seller.settings.general.index',
        'sort'  => 8,
    ],

    /*
    |--------------------------------------------------------------------------
    | Communication
    |--------------------------------------------------------------------------
    |
    | All ACLs related to communication will be placed here.
    |
    */
    [
        'key'   => 'communication',
        'name'  => 'marketplace::app.seller.acl.communication',
        'route' => 'seller.communication.index',
        'sort'  => 8,
    ],

    /*
    |--------------------------------------------------------------------------
    | Reporting
    |--------------------------------------------------------------------------
    |
    | All ACLs related to reporting will be placed here.
    |
    */
    [
        'key'   => 'reporting',
        'name'  => 'marketplace::app.seller.acl.reporting',
        'route' => 'seller.reporting.sales.index',
        'sort'  => 9,
    ], [
        'key'   => 'reporting.sales',
        'name'  => 'marketplace::app.seller.acl.sales',
        'route' => 'seller.reporting.sales.index',
        'sort'  => 1,
    ], [
        'key'   => 'reporting.customers',
        'name'  => 'marketplace::app.seller.acl.customers',
        'route' => 'seller.reporting.customers.index',
        'sort'  => 2,
    ], [
        'key'   => 'reporting.products',
        'name'  => 'marketplace::app.seller.acl.products',
        'route' => 'seller.reporting.products.index',
        'sort'  => 3,
    ],
];
