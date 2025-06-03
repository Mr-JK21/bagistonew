<?php

namespace Webkul\Marketplace\Helpers\Reporting;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Webkul\Admin\Helpers\Reporting\AbstractReporting;
use Webkul\Customer\Repositories\CustomerRepository;
use Webkul\Marketplace\Repositories\OrderRepository;
use Webkul\Marketplace\Repositories\ProductReviewRepository;

class Customer extends AbstractReporting
{
    /**
     * Create a helper instance.
     *
     * @return void
     */
    public function __construct(
        protected OrderRepository $orderRepository,
        protected ProductReviewRepository $productReviewRepository,
        protected CustomerRepository $customerRepository
    ) {
        parent::__construct();
    }

    /**
     * Retrieves total customers and their progress.
     *
     * @param  object  $seller
     */
    public function getTotalCustomersProgress($seller): array
    {
        return [
            'previous' => $previous = $this->getTotalCustomers($seller, $this->lastStartDate, $this->lastEndDate),
            'current'  => $current = $this->getTotalCustomers($seller, $this->startDate, $this->endDate),
            'progress' => $this->getPercentageChange($previous, $current),
        ];
    }

    /**
     * Returns previous customers over time
     *
     * @param  object  $seller
     * @param  string  $period
     * @param  bool  $includeEmpty
     */
    public function getPreviousTotalCustomersOverTime($seller, $period = 'auto', $includeEmpty = true): array
    {
        return $this->getTotalCustomersOverTime($seller, $this->lastStartDate, $this->lastEndDate, $period);
    }

    /**
     * Returns current customers over time
     *
     * @param  object  $seller
     * @param  string  $period
     * @param  bool  $includeEmpty
     */
    public function getCurrentTotalCustomersOverTime($seller, $period = 'auto', $includeEmpty = true): array
    {
        return $this->getTotalCustomersOverTime($seller, $this->startDate, $this->endDate, $period);
    }

    /**
     * Retrieves total customers by date
     *
     * @param  object  $seller
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     */
    public function getTotalCustomers($seller, $startDate, $endDate): int
    {
        return $this->orderRepository
            ->leftJoin('orders', 'orders.id', '=', 'marketplace_orders.order_id')
            ->leftJoin('customers', 'customers.id', '=', 'orders.customer_id')
            ->where('marketplace_orders.marketplace_seller_id', $seller->id)
            ->whereBetween('marketplace_orders.created_at', [$startDate, $endDate])
            ->selectRaw('COUNT(DISTINCT orders.customer_id) as total_customers')
            ->value('total_customers');
    }

    /**
     * Retrieves total reviews and their progress.
     *
     * @param  object  $seller
     */
    public function getTotalReviewsProgress($seller): array
    {
        return [
            'previous' => $previous = $this->getTotalReviews($seller, $this->lastStartDate, $this->lastEndDate),
            'current'  => $current = $this->getTotalReviews($seller, $this->startDate, $this->endDate),
            'progress' => $this->getPercentageChange($previous, $current),
        ];
    }

    /**
     * Retrieves total reviews by date
     *
     * @param  object  $seller
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     */
    public function getTotalReviews($seller, $startDate, $endDate): int
    {
        return $this->productReviewRepository
            ->resetModel()
            ->leftJoin('product_reviews', 'marketplace_product_reviews.product_review_id', '=', 'product_reviews.id')
            ->leftJoin('product_channels', 'product_reviews.product_id', '=', 'product_channels.product_id')
            ->where('product_reviews.status', 'approved')
            ->where('marketplace_product_reviews.marketplace_seller_id', $seller->id)
            ->whereIn('channel_id', $this->channelIds)
            ->whereBetween('product_reviews.created_at', [$startDate, $endDate])
            ->count();
    }

    /**
     * Gets customer with most sales.
     *
     * @param  object  $seller
     * @param  int  $limit
     */
    public function getCustomersWithMostSales($seller, $limit): Collection
    {
        $tablePrefix = DB::getTablePrefix();

        return $this->orderRepository
            ->leftJoin('orders', 'marketplace_orders.order_id', '=', 'orders.id')
            ->leftJoin('customers', 'customers.id', 'orders.customer_id')
            ->leftJoin('customer_groups', 'customer_groups.id', 'customers.customer_group_id')
            ->addSelect(
                'orders.customer_id as id',
                'orders.customer_email as email',
                DB::raw('CONCAT('.$tablePrefix.'orders.customer_first_name, " ", '.$tablePrefix.'orders.customer_last_name) as full_name'),
                'customer_groups.name as group_name',
                DB::raw('SUM(marketplace_orders.base_sub_total_invoiced - marketplace_orders.base_sub_total_refunded + marketplace_orders.base_tax_amount_invoiced - marketplace_orders.base_tax_amount_refunded) as total'),
                DB::raw('COUNT(DISTINCT orders.id) as orders')
            )
            ->where('marketplace_orders.marketplace_seller_id', $seller->id)
            ->whereBetween('orders.created_at', [$this->startDate, $this->endDate])
            ->groupBy(DB::raw('CONCAT(customer_email, "-", customer_id)'))
            ->orderByDesc('total')
            ->limit($limit)
            ->get();
    }

    /**
     * Gets customer with most orders.
     *
     * @param  int  $limit
     */
    public function getCustomersWithMostOrders($seller, $limit = null): Collection
    {
        $tablePrefix = DB::getTablePrefix();

        return $this->orderRepository
            ->resetModel()
            ->leftJoin('orders', 'marketplace_orders.order_id', '=', 'orders.id')
            ->addSelect(
                'orders.customer_id as id',
                'orders.customer_email as email',
                DB::raw('CONCAT('.$tablePrefix.'orders.customer_first_name, " ", '.$tablePrefix.'orders.customer_last_name) as full_name'),
                DB::raw('COUNT(*) as orders')
            )
            ->whereIn('orders.channel_id', $this->channelIds)
            ->where('marketplace_orders.marketplace_seller_id', $seller->id)
            ->whereBetween('marketplace_orders.created_at', [$this->startDate, $this->endDate])
            ->groupBy(DB::raw('CONCAT(customer_email, "-", customer_id)'))
            ->orderByDesc('orders')
            ->limit($limit)
            ->get();
    }

    /**
     * Gets customer with most orders.
     *
     * @param  object  $seller
     * @param  int  $limit
     */
    public function getCustomersWithMostReviews($seller, $limit = null): Collection
    {
        $tablePrefix = DB::getTablePrefix();

        return $this->productReviewRepository
            ->resetModel()
            ->leftJoin('product_reviews', 'marketplace_product_reviews.product_review_id', '=', 'product_reviews.id')
            ->leftJoin('customers', 'product_reviews.customer_id', '=', 'customers.id')
            ->leftJoin('product_channels', 'product_reviews.product_id', '=', 'product_channels.product_id')
            ->addSelect(
                'customers.id as id',
                'customers.email as email',
                DB::raw('CONCAT('.$tablePrefix.'customers.first_name, " ", '.$tablePrefix.'customers.last_name) as full_name'),
                DB::raw('COUNT(*) as reviews')
            )
            ->whereIn('customers.channel_id', $this->channelIds)
            ->whereIn('product_channels.channel_id', $this->channelIds)
            ->where('marketplace_product_reviews.marketplace_seller_id', $seller->id)
            ->whereBetween('product_reviews.created_at', [$this->startDate, $this->endDate])
            ->where('product_reviews.status', 'approved')
            ->whereNotNull('product_reviews.customer_id')
            ->groupBy(DB::raw('CONCAT(email, "-", '.$tablePrefix.'customers.id)'))
            ->orderByDesc('reviews')
            ->limit($limit)
            ->get();
    }

    /**
     * Gets customer with most sales.
     *
     * @param  int  $limit
     */
    public function getGroupsWithMostCustomers($seller, $limit = null): Collection
    {
        return $this->customerRepository
            ->resetModel()
            ->leftJoin('orders', 'orders.customer_id', '=', 'customers.id')
            ->leftJoin('marketplace_orders', 'marketplace_orders.order_id', '=', 'orders.id')
            ->leftJoin('customer_groups', 'customers.customer_group_id', '=', 'customer_groups.id')
            ->select('customer_groups.name as group_name')
            ->addSelect(DB::raw('COUNT(DISTINCT customers.id) as total'))
            ->whereIn('customers.channel_id', $this->channelIds)
            ->where('marketplace_orders.marketplace_seller_id', $seller->id)
            ->whereBetween('customers.created_at', [$this->startDate, $this->endDate])
            ->groupBy('customer_groups.id')
            ->orderByDesc('total')
            ->limit($limit)
            ->get();
    }

    /**
     * Returns over time stats.
     *
     * @param  object  $seller
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     * @param  string  $period
     */
    public function getTotalCustomersOverTime($seller, $startDate, $endDate, $period = 'auto'): array
    {
        $config = $this->getTimeInterval($startDate, $endDate, $period);

        $groupColumn = str_replace('(', '(customers.', $config['group_column']);

        $results = $this->orderRepository
            ->resetModel()
            ->leftJoin('orders', 'marketplace_orders.order_id', '=', 'orders.id')
            ->leftJoin('customers', 'customers.id', 'orders.customer_id')
            ->select(
                DB::raw("$groupColumn AS date"),
                DB::raw('COUNT(DISTINCT customers.id) AS total')
            )
            ->whereIn('customers.channel_id', $this->channelIds)
            ->where('marketplace_orders.marketplace_seller_id', $seller->id)
            ->whereBetween('customers.created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->get();

        $stats = [];

        foreach ($config['intervals'] as $interval) {
            $total = $results->where('date', $interval['filter'])->first();

            $stats[] = [
                'label' => $interval['start'],
                'total' => $total?->total ?? 0,
            ];
        }

        return $stats;
    }
}
