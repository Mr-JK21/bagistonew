<?php

namespace Webkul\Marketplace\Helpers;

use Illuminate\Support\Carbon;
use Webkul\Marketplace\DataGrids\Seller\OrderDataGrid;
use Webkul\Marketplace\Helpers\Reporting\Customer;
use Webkul\Marketplace\Helpers\Reporting\Product;
use Webkul\Marketplace\Helpers\Reporting\Sale;
use Webkul\Marketplace\Helpers\Reporting\Visitor;

class Dashboard
{
    /**
     * Create a controller instance.
     *
     * @return void
     */
    public function __construct(
        protected Customer $customerReporting,
        protected Product $productReporting,
        protected Sale $saleReporting,
        protected Visitor $visitorReporting
    ) {}

    /**
     * Returns the overall statistics.
     *
     * @param  object  $seller
     */
    public function getOverAllStats($seller): array
    {
        return [
            'total_customers'  => $this->customerReporting->getTotalCustomersProgress($seller),
            'total_orders'     => $this->saleReporting->getTotalOrdersProgress($seller),
            'total_sales'      => $this->saleReporting->getTotalSalesProgress($seller),
            'avg_sales'        => $this->saleReporting->getAverageSalesProgress($seller),
            'total_payout'     => $this->saleReporting->getTotalPayoutProgress($seller),
            'remaining_payout' => $this->saleReporting->getRemainingPayoutProgress($seller),
        ];
    }

    /**
     * Returns the top customers statistics.
     *
     * @param  object  $seller
     * @param  int  $limit
     */
    public function getTopCustomers($seller, $limit = 5): object
    {
        return $this->customerReporting->getCustomersWithMostSales($seller, $limit);
    }

    /**
     * Returns top products statistics.
     */
    public function getTopProducts($seller): object
    {
        return $this->productReporting->getTopSellingProductsByRevenue($seller);
    }

    /**
     * Returns top categories statistics.
     */
    public function getTopCategories($seller): object
    {
        return $this->productReporting->getTopSellingCategoriesByRevenue($seller);
    }

    /**
     * Returns top stock threshold products statistics.
     */
    public function getStockThreshold($seller): object
    {
        return $this->productReporting->getStockThresholdProducts($seller);
    }

    /**
     * Returns sales statistics.
     *
     * @param  object  $seller
     */
    public function getSalesStats($seller): array
    {
        return [
            'total_orders' => $this->saleReporting->getTotalOrdersProgress($seller),
            'total_sales'  => $this->saleReporting->getTotalSalesProgress($seller),
            'over_time'    => $this->saleReporting->getCurrentTotalSalesOverTime($seller),
        ];
    }

    /**
     * Returns visitors statistics.
     */
    public function getVisitorStats($seller): array
    {
        return [
            'total'     => $this->visitorReporting->getTotalVisitorsProgress($seller),
            'unique'    => $this->visitorReporting->getTotalUniqueVisitorsProgress($seller),
            'over_time' => $this->visitorReporting->getCurrentTotalVisitorsOverTime($seller),
        ];
    }

    /**
     * Get the start date.
     *
     * @return \Carbon\Carbon
     */
    public function getStartDate(): Carbon
    {
        return $this->saleReporting->getStartDate();
    }

    /**
     * Get the end date.
     *
     * @return \Carbon\Carbon
     */
    public function getEndDate(): Carbon
    {
        return $this->saleReporting->getEndDate();
    }

    /**
     * Get the orders.
     *
     * @param  object  $seller
     * @return Collection
     */
    public function getRecentOrders($seller)
    {
        $orders = [];

        foreach (['pending', 'processing', 'completed', 'refunded'] as $status) {
            $orders[$status] = app(OrderDataGrid::class)
                ->prepareQueryBuilder()
                ->where('marketplace_orders.status', $status)
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get()
                ->map(function ($order) {
                    $order->payment_method = core()->getConfigData("sales.payment_methods.$order->payment_method.title");

                    $method = explode('_', $order->shipping_method);
                    $order->shipping_method = core()->getConfigData('sales.carriers.'.current($method).'.title');

                    return $order;
                });
        }

        return $orders;
    }

    /**
     * Returns date range
     */
    public function getDateRange(): string
    {
        return $this->getStartDate()->format('d M').' - '.$this->getEndDate()->format('d M');
    }
}
