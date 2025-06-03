<?php

namespace Webkul\Marketplace\Helpers\Reporting;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Webkul\Admin\Helpers\Reporting\AbstractReporting;
use Webkul\Checkout\Repositories\CartItemRepository;
use Webkul\Checkout\Repositories\CartRepository;

class Cart extends AbstractReporting
{
    /**
     * Create a helper instance.
     *
     * @return void
     */
    public function __construct(
        protected CartRepository $cartRepository,
        protected CartItemRepository $cartItemRepository
    ) {
        parent::__construct();
    }

    /**
     * Retrieves total carts and their progress.
     *
     * @return array
     */
    public function getTotalCartsProgress($seller)
    {
        return [
            'previous' => $previous = $this->getTotalCarts($seller, $this->lastStartDate, $this->lastEndDate),
            'current'  => $current = $this->getTotalCarts($seller, $this->startDate, $this->endDate),
            'progress' => $this->getPercentageChange($previous, $current),
        ];
    }

    /**
     * Retrieves today carts and their progress.
     */
    public function getTodayCartsProgress($seller): array
    {
        return [
            'previous' => $previous = $this->getTotalCarts($seller, now()->subDay()->startOfDay(), now()->subDay()->endOfDay()),
            'current'  => $current = $this->getTotalCarts($seller, now()->today(), now()->endOfDay()),
            'progress' => $this->getPercentageChange($previous, $current),
        ];
    }

    /**
     * Retrieves total abandoned sales and their progress.
     *
     * @param  object  $seller
     * @return array
     */
    public function getTotalAbandonedSalesProgress($seller)
    {
        return [
            'previous'        => $previous = $this->getTotalAbandonedSales($seller, $this->lastStartDate, $this->lastEndDate),
            'current'         => $current = $this->getTotalAbandonedSales($seller, $this->startDate, $this->endDate),
            'formatted_total' => core()->formatBasePrice($current),
            'progress'        => $this->getPercentageChange($previous, $current),
        ];
    }

    /**
     * Retrieves total abandoned carts and their progress.
     *
     * @param  object  $seller
     * @return array
     */
    public function getTotalAbandonedCartsProgress($seller)
    {
        return [
            'previous' => $previous = $this->getTotalAbandonedCarts($seller, $this->lastStartDate, $this->lastEndDate),
            'current'  => $current = $this->getTotalAbandonedCarts($seller, $this->startDate, $this->endDate),
            'progress' => $this->getPercentageChange($previous, $current),
        ];
    }

    /**
     * Retrieves total abandoned carts rate and their progress.
     *
     * @param  object  $seller
     * @return array
     */
    public function getTotalAbandonedCartRateProgress($seller)
    {
        return [
            'previous' => $previous = $this->getTotalAbandonedCartRate($seller, $this->lastStartDate, $this->lastEndDate),
            'current'  => $current = $this->getTotalAbandonedCartRate($seller, $this->startDate, $this->endDate),
            'progress' => $this->getPercentageChange($previous, $current),
        ];
    }

    /**
     * Retrieves total carts
     *
     * @param  object  $seller
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     */
    public function getTotalCarts($seller, $startDate, $endDate): int
    {
        return $this->cartRepository
            ->resetModel()
            ->leftJoin('cart_items', 'cart.id', '=', 'cart_items.cart_id')
            ->whereIn('channel_id', $this->channelIds)
            ->whereJsonContains('cart_items.additional->seller_info->seller_id', $seller->id)
            ->whereBetween('cart.created_at', [$startDate, $endDate])
            ->count();
    }

    /**
     * Retrieves total abandoned carts
     *
     * @param  object  $seller
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     */
    public function getTotalAbandonedCarts($seller, $startDate, $endDate): int
    {
        return $this->cartRepository
            ->resetModel()
            ->leftJoin('cart_items', 'cart.id', '=', 'cart_items.cart_id')
            ->where('is_active', 1)
            ->whereIn('channel_id', $this->channelIds)
            ->whereJsonContains('cart_items.additional->seller_info->seller_id', $seller->id)
            ->whereBetween('cart.created_at', [$startDate, $endDate->subDays(2)])
            ->count();
    }

    /**
     * Retrieves total abandoned cart rate
     *
     * @param  object  $seller
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     */
    public function getTotalAbandonedCartRate($seller, $startDate, $endDate): float
    {
        $totalCarts = $this->getTotalCarts($seller, $startDate, $endDate);

        if (! $totalCarts) {
            return 0;
        }

        return ($this->getTotalAbandonedCarts($seller, $startDate, $endDate) * 100) / $totalCarts;
    }

    /**
     * Retrieves total abandoned sales
     *
     * @param  object  $seller
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     */
    public function getTotalAbandonedSales($seller, $startDate, $endDate): int
    {
        return $this->cartRepository
            ->resetModel()
            ->leftJoin('cart_items', 'cart.id', '=', 'cart_items.cart_id')
            ->where('is_active', 1)
            ->whereIn('channel_id', $this->channelIds)
            ->whereJsonContains('cart_items.additional->seller_info->seller_id', $seller->id)
            ->whereBetween('cart.created_at', [$startDate, $endDate->subDays(2)])
            ->sum('base_grand_total');
    }

    /**
     * Retrieves abandoned cart products
     *
     * @param  object  $seller
     * @param  int  $limit
     */
    public function getAbandonedCartProducts($seller, $limit = null): Collection
    {
        return $this->cartItemRepository
            ->resetModel()
            ->leftJoin('cart', 'cart_items.cart_id', '=', 'cart.id')
            ->select('product_id as id', 'name')
            ->addSelect(DB::raw('COUNT(*) as count'))
            ->where('is_active', 1)
            ->whereIn('cart.channel_id', $this->channelIds)
            ->whereJsonContains('cart_items.additional->seller_info->seller_id', $seller->id)
            ->whereBetween('cart.created_at', [$this->startDate, $this->endDate->subDays(2)])
            ->groupBy('product_id')
            ->limit($limit)
            ->orderByDesc('count')
            ->get();
    }

    /**
     * Retrieves total abandoned cart products
     *
     * @param  object  $seller
     */
    public function getTotalAbandonedCartProducts($seller): int
    {
        return $this->cartItemRepository
            ->resetModel()
            ->distinct('product_id')
            ->leftJoin('cart', 'cart_items.cart_id', '=', 'cart.id')
            ->where('is_active', 1)
            ->whereJsonContains('cart_items.additional->seller_info->seller_id', $seller->id)
            ->whereIn('cart.channel_id', $this->channelIds)
            ->whereBetween('cart.created_at', [$this->startDate, $this->endDate->subDays(2)])
            ->count();
    }

    /**
     * Retrieves total unique cart users
     *
     * @param  object  $seller
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     */
    public function getTotalUniqueCartsUsers($seller, $startDate, $endDate): int
    {
        return $this->cartRepository
            ->resetModel()
            ->leftJoin('cart_items', 'cart.id', '=', 'cart_items.cart_id')
            ->groupBy(DB::raw('CONCAT(customer_email, "-", customer_id)'))
            ->whereIn('cart.channel_id', $this->channelIds)
            ->whereJsonContains('cart_items.additional->seller_info->seller_id', $seller->id)
            ->whereBetween('cart_items.created_at', [$startDate, $endDate])
            ->get()
            ->count();
    }
}
