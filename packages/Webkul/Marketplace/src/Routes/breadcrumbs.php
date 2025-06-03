<?php

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

/**
 * ----------------------------------------------
 * Seller Account Breadcrumbs
 * ----------------------------------------------
 */
Breadcrumbs::for('seller_account', function (BreadcrumbTrail $trail) {
    $trail->push(trans('marketplace::app.seller.breadcrumb.title'), route('seller.dashboard.index'));
});

/**
 * ----------------------------------------------
 * Dashboard Breadcrumbs
 * ----------------------------------------------
 */
Breadcrumbs::for('seller_dashboard', function (BreadcrumbTrail $trail) {
    $trail->parent('seller_account');

    $trail->push(trans('marketplace::app.seller.breadcrumb.dashboard'));
});

/**
 * ----------------------------------------------
 * Orders Breadcrumbs
 * ----------------------------------------------
 */
Breadcrumbs::for('seller_orders', function (BreadcrumbTrail $trail) {
    $trail->parent('seller_account');

    $trail->push(trans('marketplace::app.seller.breadcrumb.orders'), route('seller.orders.index'));
});

Breadcrumbs::for('seller_order_view', function (BreadcrumbTrail $trail) {
    $trail->parent('seller_orders');

    $trail->push(trans('marketplace::app.seller.breadcrumb.view'));
});

/**
 * ----------------------------------------------
 * Transaction Breadcrumbs
 * ----------------------------------------------
 */
Breadcrumbs::for('seller_transactions', function (BreadcrumbTrail $trail) {
    $trail->parent('seller_account');

    $trail->push(trans('marketplace::app.seller.breadcrumb.transaction'), route('seller.transactions.index'));
});

Breadcrumbs::for('seller_transactions_view', function (BreadcrumbTrail $trail) {
    $trail->parent('seller_transactions');

    $trail->push(trans('marketplace::app.seller.breadcrumb.view'));
});

/**
 * ----------------------------------------------
 * Products Breadcrumbs
 * ----------------------------------------------
 */
Breadcrumbs::for('seller_products', function (BreadcrumbTrail $trail) {
    $trail->parent('seller_account');

    $trail->push(trans('marketplace::app.seller.breadcrumb.products'), route('seller.products.index'));
});

Breadcrumbs::for('seller_product_add', function (BreadcrumbTrail $trail) {
    $trail->parent('seller_products');

    $trail->push(trans('marketplace::app.seller.breadcrumb.add-new-product'));
});

Breadcrumbs::for('seller_product_assign', function (BreadcrumbTrail $trail) {
    $trail->parent('seller_products');

    $trail->push(trans('marketplace::app.seller.breadcrumb.assign'));
});

Breadcrumbs::for('seller_product_edit', function (BreadcrumbTrail $trail) {
    $trail->parent('seller_products');

    $trail->push(trans('marketplace::app.seller.breadcrumb.edit'));
});

/**
 * ----------------------------------------------
 * Products Reviews Breadcrumbs
 * ----------------------------------------------
 */
Breadcrumbs::for('seller_product_reviews', function (BreadcrumbTrail $trail) {
    $trail->parent('seller_account');

    $trail->push(trans('marketplace::app.seller.breadcrumb.product-reviews'));
});

/**
 * ----------------------------------------------
 * Customers Breadcrumbs
 * ----------------------------------------------
 */
Breadcrumbs::for('seller_customers', function (BreadcrumbTrail $trail) {
    $trail->parent('seller_account');

    $trail->push(trans('marketplace::app.seller.breadcrumb.customers'));
});

/**
 * ----------------------------------------------
 * Profile Breadcrumbs
 * ----------------------------------------------
 */
Breadcrumbs::for('seller_profile', function (BreadcrumbTrail $trail) {
    $trail->parent('seller_account');

    $trail->push(trans('marketplace::app.seller.breadcrumb.manage-profile'));
});

/**
 * ----------------------------------------------
 * Seller Review Breadcrumbs
 * ----------------------------------------------
 */
Breadcrumbs::for('seller_review', function (BreadcrumbTrail $trail) {
    $trail->parent('seller_account');

    $trail->push(trans('marketplace::app.seller.breadcrumb.seller-reviews'));
});

/**
 * ----------------------------------------------
 * Settings Breadcrumbs
 * ----------------------------------------------
 */
Breadcrumbs::for('settings', function (BreadcrumbTrail $trail) {
    $trail->parent('seller_account');

    $trail->push(trans('marketplace::app.seller.breadcrumb.settings'), route('seller.settings.general.index'));
});

Breadcrumbs::for('general', function (BreadcrumbTrail $trail) {
    $trail->parent('settings');

    $trail->push(trans('marketplace::app.seller.breadcrumb.general'));
});

Breadcrumbs::for('users', function (BreadcrumbTrail $trail) {
    $trail->parent('settings');

    $trail->push(trans('marketplace::app.seller.breadcrumb.users'));
});

Breadcrumbs::for('roles', function (BreadcrumbTrail $trail) {
    $trail->parent('settings');

    $trail->push(trans('marketplace::app.seller.breadcrumb.roles'), route('seller.settings.roles.index'));
});

Breadcrumbs::for('roles.create', function (BreadcrumbTrail $trail) {
    $trail->parent('roles');

    $trail->push(trans('marketplace::app.seller.breadcrumb.create'));
});

Breadcrumbs::for('roles.edit', function (BreadcrumbTrail $trail) {
    $trail->parent('roles');

    $trail->push(trans('marketplace::app.seller.breadcrumb.edit'));
});

/**
 * ----------------------------------------------
 * Communication Breadcrumbs
 * ----------------------------------------------
 */
Breadcrumbs::for('communication', function (BreadcrumbTrail $trail) {
    $trail->parent('seller_account');

    $trail->push(trans('marketplace::app.seller.breadcrumb.communication'));
});

/**
 * ----------------------------------------------
 * Reporting Breadcrumbs
 * ----------------------------------------------
 */
Breadcrumbs::for('reporting', function (BreadcrumbTrail $trail) {
    $trail->parent('seller_account');

    $trail->push(trans('marketplace::app.seller.breadcrumb.reporting'), route('seller.reporting.sales.index'));
});

Breadcrumbs::for('reporting.sales', function (BreadcrumbTrail $trail) {
    $trail->parent('reporting');

    $trail->push(trans('marketplace::app.seller.breadcrumb.sales'), route('seller.reporting.sales.index'));
});

Breadcrumbs::for('reporting.customers', function (BreadcrumbTrail $trail) {
    $trail->parent('reporting');

    $trail->push(trans('marketplace::app.seller.breadcrumb.customers'), route('seller.reporting.customers.index'));
});

Breadcrumbs::for('reporting.products', function (BreadcrumbTrail $trail) {
    $trail->parent('reporting');

    $trail->push(trans('marketplace::app.seller.breadcrumb.products'), route('seller.reporting.products.index'));
});
