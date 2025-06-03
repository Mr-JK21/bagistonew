<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Marketplace ACL
    |--------------------------------------------------------------------------
    |
    | All ACLs related to marketplace will be placed here.
    |
    */
    [
        'key'   => 'marketplace',
        'name'  => 'marketplace::app.admin.acl.title',
        'route' => 'admin.marketplace.sellers.index',
        'sort'  => 2,
    ],

    /*
    |--------------------------------------------------------------------------
    | Marketplace Seller ACL
    |--------------------------------------------------------------------------
    |
    | All ACLs related to sellers will be placed here.
    |
    */
    [
        'key'   => 'marketplace.sellers',
        'name'  => 'marketplace::app.admin.acl.sellers',
        'route' => 'admin.marketplace.sellers.index',
        'sort'  => 1,
    ], [
        'key'   => 'marketplace.sellers.create',
        'name'  => 'marketplace::app.admin.acl.create',
        'route' => 'admin.marketplace.sellers.create',
        'sort'  => 1,
    ], [
        'key'   => 'marketplace.sellers.edit',
        'name'  => 'marketplace::app.admin.acl.edit',
        'route' => 'admin.marketplace.seller.edit',
        'sort'  => 2,
    ], [
        'key'   => 'marketplace.sellers.delete',
        'name'  => 'marketplace::app.admin.acl.delete',
        'route' => 'admin.marketplace.sellers.delete',
        'sort'  => 3,
    ], [
        'key'   => 'marketplace.sellers.assign_product',
        'name'  => 'marketplace::app.admin.acl.assign-product',
        'route' => 'admin.marketplace.seller.assign_product',
        'sort'  => 4,
    ],

    /*
    |--------------------------------------------------------------------------
    | Marketplace Product ACL
    |--------------------------------------------------------------------------
    |
    | All ACLs related to products will be placed here.
    |
    */
    [
        'key'   => 'marketplace.products',
        'name'  => 'marketplace::app.admin.acl.products',
        'route' => 'admin.marketplace.products.index',
        'sort'  => 2,
    ], [
        'key'   => 'marketplace.products.edit',
        'name'  => 'marketplace::app.admin.acl.edit',
        'route' => 'admin.marketplace.products.mass_update',
        'sort'  => 2,
    ], [
        'key'   => 'marketplace.products.delete',
        'name'  => 'marketplace::app.admin.acl.delete',
        'route' => 'admin.marketplace.products.delete',
        'sort'  => 3,
    ],

    /*
    |--------------------------------------------------------------------------
    | Marketplace Seller Categories ACL
    |--------------------------------------------------------------------------
    |
    | All ACLs related to seller categories will be placed here.
    |
    */
    [
        'key'   => 'marketplace.seller-categories',
        'name'  => 'marketplace::app.admin.acl.seller-categories',
        'route' => 'admin.marketplace.seller_categories.index',
        'sort'  => 3,
    ], [
        'key'   => 'marketplace.seller-categories.create',
        'name'  => 'marketplace::app.admin.acl.create',
        'route' => 'admin.marketplace.seller_categories.create',
        'sort'  => 1,
    ], [
        'key'   => 'marketplace.seller-categories.edit',
        'name'  => 'marketplace::app.admin.acl.edit',
        'route' => 'admin.marketplace.seller_categories.edit',
        'sort'  => 2,
    ], [
        'key'   => 'marketplace.seller-categories.delete',
        'name'  => 'marketplace::app.admin.acl.delete',
        'route' => 'admin.marketplace.seller_categories.delete',
        'sort'  => 3,
    ],

    /*
    |--------------------------------------------------------------------------
    | Marketplace Orders ACL
    |--------------------------------------------------------------------------
    |
    | All ACLs related to orders will be placed here.
    |
    */
    [
        'key'   => 'marketplace.orders',
        'name'  => 'marketplace::app.admin.acl.orders',
        'route' => 'admin.marketplace.orders.index',
        'sort'  => 4,
    ], [
        'key'   => 'marketplace.orders.view',
        'name'  => 'marketplace::app.admin.acl.view',
        'route' => 'admin.sales.orders.view',
        'sort'  => 1,
    ],

    /*
    |--------------------------------------------------------------------------
    | Marketplace Payment Request ACL
    |--------------------------------------------------------------------------
    |
    | All ACLs related to payment requests will be placed here.
    |
    */
    [
        'key'   => 'marketplace.payment-requests',
        'name'  => 'marketplace::app.admin.acl.payment-request',
        'route' => 'admin.marketplace.payment_requests.index',
        'sort'  => 5,
    ], [
        'key'   => 'marketplace.payment-requests.pay',
        'name'  => 'marketplace::app.admin.acl.pay',
        'route' => 'admin.marketplace.payment_requests.pay',
        'sort'  => 1,
    ],

    /*
    |--------------------------------------------------------------------------
    | Marketplace Transactions ACL
    |--------------------------------------------------------------------------
    |
    | All ACLs related to transactions will be placed here.
    |
    */
    [
        'key'   => 'marketplace.transactions',
        'name'  => 'marketplace::app.admin.acl.transactions',
        'route' => 'admin.marketplace.transactions.index',
        'sort'  => 6,
    ], [
        'key'   => 'marketplace.transactions.view',
        'name'  => 'marketplace::app.admin.acl.view',
        'route' => 'admin.marketplace.transactions.view',
        'sort'  => 1,
    ],

    /*
    |--------------------------------------------------------------------------
    | Marketplace Product Review ACL
    |--------------------------------------------------------------------------
    |
    | All ACLs related to product reviews will be placed here.
    |
    */
    [
        'key'   => 'marketplace.product-reviews',
        'name'  => 'marketplace::app.admin.acl.product-reviews',
        'route' => 'admin.marketplace.product_reviews.index',
        'sort'  => 7,
    ], [
        'key'   => 'marketplace.product-reviews.edit',
        'name'  => 'marketplace::app.admin.acl.edit',
        'route' => 'admin.marketplace.product_reviews.mass_update',
        'sort'  => 1,
    ], [
        'key'   => 'marketplace.product-reviews.delete',
        'name'  => 'marketplace::app.admin.acl.delete',
        'route' => 'admin.marketplace.product_reviews.delete',
        'sort'  => 2,
    ],

    /*
    |--------------------------------------------------------------------------
    | Marketplace Seller Review ACL
    |--------------------------------------------------------------------------
    |
    | All ACLs related to seller reviews will be placed here.
    |
    */
    [
        'key'   => 'marketplace.seller-reviews',
        'name'  => 'marketplace::app.admin.acl.reviews',
        'route' => 'admin.marketplace.seller_reviews.index',
        'sort'  => 8,
    ], [
        'key'   => 'marketplace.seller-reviews.edit',
        'name'  => 'marketplace::app.admin.acl.edit',
        'route' => 'admin.marketplace.seller_reviews.mass_update',
        'sort'  => 1,
    ], [
        'key'   => 'marketplace.seller-reviews.delete',
        'name'  => 'marketplace::app.admin.acl.delete',
        'route' => 'admin.marketplace.seller_reviews.delete',
        'sort'  => 2,
    ],

    /*
    |--------------------------------------------------------------------------
    | Marketplace Flag Reasons ACL
    |--------------------------------------------------------------------------
    |
    | All ACLs related to flag reasons will be placed here.
    |
    */
    [
        'key'   => 'marketplace.flag-reasons',
        'name'  => 'marketplace::app.admin.acl.flag-reasons',
        'route' => 'admin.marketplace.flag_reasons.index',
        'sort'  => 9,
    ], [
        'key'   => 'marketplace.flag-reasons.create',
        'name'  => 'marketplace::app.admin.acl.create',
        'route' => 'admin.marketplace.flag_reasons.create',
        'sort'  => 1,
    ], [
        'key'   => 'marketplace.flag-reasons.edit',
        'name'  => 'marketplace::app.admin.acl.edit',
        'route' => 'admin.marketplace.flag_reasons.edit',
        'sort'  => 2,
    ], [
        'key'   => 'marketplace.flag-reasons.delete',
        'name'  => 'marketplace::app.admin.acl.delete',
        'route' => 'admin.marketplace.flag_reasons.delete',
        'sort'  => 3,
    ],

    /*
    |--------------------------------------------------------------------------
    | Marketplace Communications ACL
    |--------------------------------------------------------------------------
    |
    | All ACLs related to communications will be placed here.
    |
    */
    [
        'key'   => 'marketplace.communications',
        'name'  => 'marketplace::app.admin.acl.communications',
        'route' => 'admin.marketplace.communications.index',
        'sort'  => 10,
    ],
];
