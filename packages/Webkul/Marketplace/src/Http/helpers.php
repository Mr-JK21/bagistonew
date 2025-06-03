<?php

use Webkul\Marketplace\Acl;
use Webkul\Marketplace\Menu;
use Webkul\Marketplace\Seller;
use Webkul\Marketplace\Visitor;

/**
 * -------------------------
 * Marketplace Acl helper.
 * -------------------------
 */
if (! function_exists('marketplace_acl')) {
    function marketplace_acl(): Acl
    {
        return app(Acl::class);
    }
}

/**
 * -------------------------
 * Marketplace Menu helper.
 * -------------------------
 */
if (! function_exists('marketplace_menu')) {
    function marketplace_menu(): Menu
    {
        return app(Menu::class);
    }
}

/**
 * -------------------------
 * Seller helper.
 * -------------------------
 */
if (! function_exists('seller')) {
    function seller(): Seller
    {
        return app(Seller::class);
    }
}

/**
 * -------------------------
 * Visitor helper.
 * -------------------------
 */
if (! function_exists('marketplace_visitor')) {
    function marketplace_visitor(): Visitor
    {
        return app(Visitor::class);
    }
}
