<?php

namespace Webkul\Marketplace\Helpers\Reporting;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Webkul\Admin\Helpers\Reporting\AbstractReporting;
use Webkul\Marketplace\Repositories\OrderItemRepository;
use Webkul\Marketplace\Repositories\OrderRepository;
use Webkul\Marketplace\Repositories\TransactionRepository;

class Sale extends AbstractReporting
{
    /**
     * Create a helper instance.
     *
     * @return void
     */
    public function __construct(
        protected OrderRepository $orderRepository,
        protected OrderItemRepository $orderItemRepository,
        protected TransactionRepository $transactionRepository,
    ) {
        parent::__construct();
    }

    /**
     * Retrieves total orders and their progress.
     *
     * @param  object  $seller
     * @return array
     */
    public function getTotalOrdersProgress($seller)
    {
        return [
            'previous' => $previous = $this->getTotalOrders($seller, $this->lastStartDate, $this->lastEndDate),
            'current'  => $current = $this->getTotalOrders($seller, $this->startDate, $this->endDate),
            'progress' => $this->getPercentageChange($previous, $current),
        ];
    }

    /**
     * Retrieves total orders
     *
     * @param  object  $seller
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     */
    public function getTotalOrders($seller, $startDate, $endDate): int
    {
        return $this->orderRepository
            ->where('marketplace_seller_id', $seller->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
    }

    /**
     * Returns orders over time
     *
     * @param  object  $seller
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     * @param  string  $period
     * @param  bool  $includeEmpty
     */
    public function getTotalOrdersOverTime($seller, $startDate, $endDate, $period, $includeEmpty): array
    {
        return $this->getOverTimeStats(
            $seller,
            $startDate,
            $endDate,
            'COUNT(*)',
            $period
        );
    }

    /**
     * Returns previous orders over time
     *
     * @param  object  $seller
     * @param  string  $period
     * @param  bool  $includeEmpty
     */
    public function getPreviousTotalOrdersOverTime($seller, $period = 'auto', $includeEmpty = true): array
    {
        return $this->getTotalOrdersOverTime($seller, $this->lastStartDate, $this->lastEndDate, $period, $includeEmpty);
    }

    /**
     * Returns current orders over time
     *
     * @param  object  $seller
     * @param  string  $period
     * @param  bool  $includeEmpty
     */
    public function getCurrentTotalOrdersOverTime($seller, $period = 'auto', $includeEmpty = true): array
    {
        return $this->getTotalOrdersOverTime($seller, $this->startDate, $this->endDate, $period, $includeEmpty);
    }

    /**
     * Retrieves total sales and their progress.
     *
     * @param  object  $seller
     */
    public function getTotalSalesProgress($seller): array
    {
        return [
            'previous'        => $previous = $this->getTotalSales($seller, $this->lastStartDate, $this->lastEndDate),
            'current'         => $current = $this->getTotalSales($seller, $this->startDate, $this->endDate),
            'formatted_total' => core()->formatBasePrice($current),
            'progress'        => $this->getPercentageChange($previous, $current),
        ];
    }

    /**
     * Retrieves sub total sales and their progress.
     */
    public function getSubTotalSalesProgress($seller): array
    {
        return [
            'previous'        => $previous = $this->getSubTotalSales($seller, $this->lastStartDate, $this->lastEndDate),
            'current'         => $current = $this->getSubTotalSales($seller, $this->startDate, $this->endDate),
            'formatted_total' => core()->formatBasePrice($current),
            'progress'        => $this->getPercentageChange($previous, $current),
        ];
    }

    /**
     * Retrieves total sales
     *
     * @param  object  $seller
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     */
    public function getTotalSales($seller, $startDate, $endDate): float
    {
        return $this->orderRepository
            ->where('marketplace_seller_id', $seller->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum(DB::raw('base_sub_total_invoiced - base_sub_total_refunded + base_tax_amount_invoiced - base_tax_amount_refunded'));
    }

    /**
     * Retrieves total sales
     *
     * @param  object  $seller
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     */
    public function getTotalSellerSales($seller, $startDate, $endDate): float
    {
        return $this->orderRepository
            ->where('marketplace_seller_id', $seller->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum(DB::raw('base_sub_total_invoiced - base_sub_total_refunded + base_tax_amount_invoiced - base_tax_amount_refunded - base_commission_invoiced'));
    }

    /**
     * Retrieves sub total sales
     *
     * @param  object  $seller
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     */
    public function getSubTotalSales($seller, $startDate, $endDate): float
    {
        return $this->orderRepository
            ->resetModel()
            ->leftJoin('orders', 'marketplace_orders.order_id', '=', 'orders.id')
            ->whereIn('orders.channel_id', $this->channelIds)
            ->where('marketplace_seller_id', $seller->id)
            ->whereBetween('marketplace_orders.created_at', [$startDate, $endDate])
            ->sum(DB::raw('marketplace_orders.base_sub_total_invoiced - marketplace_orders.base_sub_total_refunded'));
    }

    /**
     * Returns previous sales over time
     *
     * @param  object  $seller
     * @param  string  $period
     * @param  bool  $includeEmpty
     */
    public function getPreviousTotalSalesOverTime($seller, $period = 'auto', $includeEmpty = true): array
    {
        return $this->getTotalSalesOverTime($seller, $this->lastStartDate, $this->lastEndDate, $period, $includeEmpty);
    }

    /**
     * Returns current sales over time
     *
     * @param  object  $seller
     * @param  string  $period
     * @param  bool  $includeEmpty
     */
    public function getCurrentTotalSalesOverTime($seller, $period = 'auto', $includeEmpty = true): array
    {
        return $this->getTotalSalesOverTime($seller, $this->startDate, $this->endDate, $period, $includeEmpty);
    }

    /**
     * Returns sales over time
     *
     * @param  object  $seller
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     * @param  string  $period
     * @param  bool  $includeEmpty
     */
    public function getTotalSalesOverTime($seller, $startDate, $endDate, $period, $includeEmpty): array
    {
        return $this->getOverTimeStats(
            $seller,
            $startDate,
            $endDate,
            'SUM(base_sub_total_invoiced - base_sub_total_refunded)',
            $period
        );
    }

    /**
     * Retrieves total commission paid and their progress.
     *
     * @param  object  $seller
     */
    public function getTotalCommissionPaidProgress($seller): array
    {
        return [
            'previous'        => $previous = $this->getTotalCommissionPaid($seller, $this->lastStartDate, $this->lastEndDate),
            'current'         => $current = $this->getTotalCommissionPaid($seller, $this->startDate, $this->endDate),
            'formatted_total' => core()->formatBasePrice($current),
            'progress'        => $this->getPercentageChange($previous, $current),
        ];
    }

    /**
     * Retrieves total commission paid
     *
     * @param  object  $seller
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     */
    public function getTotalCommissionPaid($seller, $startDate, $endDate): float
    {
        return $this->orderRepository
            ->where('marketplace_seller_id', $seller->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum(DB::raw('base_commission_invoiced'));
    }

    /**
     * Returns previous commission over time
     *
     * @param  object  $seller
     * @param  string  $period
     * @param  bool  $includeEmpty
     */
    public function getPreviousTotalCommissionPaidOverTime($seller, $period = 'auto', $includeEmpty = true): array
    {
        return $this->getTotalCommissionPaidOverTime($seller, $this->lastStartDate, $this->lastEndDate, $period, $includeEmpty);
    }

    /**
     * Returns current commission over time
     *
     * @param  object  $seller
     * @param  string  $period
     * @param  bool  $includeEmpty
     */
    public function getCurrentTotalCommissionPaidOverTime($seller, $period = 'auto', $includeEmpty = true): array
    {
        return $this->getTotalCommissionPaidOverTime($seller, $this->startDate, $this->endDate, $period, $includeEmpty);
    }

    /**
     * Returns commission over time
     *
     * @param  object  $seller
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     * @param  string  $period
     */
    public function getTotalCommissionPaidOverTime($seller, $startDate, $endDate, $period): array
    {
        return $this->getOverTimeStats(
            $seller,
            $startDate,
            $endDate,
            'SUM(base_commission_invoiced)',
            $period
        );
    }

    /**
     * Retrieves average sales and their progress.
     *
     * @param  object  $seller
     */
    public function getAverageSalesProgress($seller): array
    {
        return [
            'previous'        => $previous = $this->getAverageSales($seller, $this->lastStartDate, $this->lastEndDate),
            'current'         => $current = $this->getAverageSales($seller, $this->startDate, $this->endDate),
            'formatted_total' => core()->formatBasePrice($current),
            'progress'        => $this->getPercentageChange($previous, $current),
        ];
    }

    /**
     * Retrieves average sales
     *
     * @param  object  $seller
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     * @return array
     */
    public function getAverageSales($seller, $startDate, $endDate): ?float
    {
        return $this->orderRepository
            ->where('marketplace_seller_id', $seller->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->avg(DB::raw('base_sub_total_invoiced - base_sub_total_refunded + base_tax_amount_invoiced - base_tax_amount_refunded'));
    }

    /**
     * Returns previous average sales over time
     *
     * @param  object  $seller
     * @param  string  $period
     * @param  bool  $includeEmpty
     */
    public function getPreviousAverageSalesOverTime($seller, $period = 'auto', $includeEmpty = true): array
    {
        return $this->getAverageSalesOverTime($seller, $this->lastStartDate, $this->lastEndDate, $period, $includeEmpty);
    }

    /**
     * Returns current average sales over time
     *
     * @param  object  $seller
     * @param  string  $period
     * @param  bool  $includeEmpty
     */
    public function getCurrentAverageSalesOverTime($seller, $period = 'auto', $includeEmpty = true): array
    {
        return $this->getAverageSalesOverTime($seller, $this->startDate, $this->endDate, $period, $includeEmpty);
    }

    /**
     * Returns average sales over time
     *
     * @param  object  $seller
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     * @param  string  $period
     * @param  bool  $includeEmpty
     */
    public function getAverageSalesOverTime($seller, $startDate, $endDate, $period, $includeEmpty): array
    {
        return $this->getOverTimeStats(
            $seller,
            $startDate,
            $endDate,
            'AVG(base_grand_total_invoiced - base_grand_total_refunded)',
            $period
        );
    }

    /**
     * Retrieves refunds and their progress.
     *
     * @param  object  $seller
     */
    public function getRefundsProgress($seller): array
    {
        return [
            'previous'        => $previous = $this->getRefunds($seller, $this->lastStartDate, $this->lastEndDate),
            'current'         => $current = $this->getRefunds($seller, $this->startDate, $this->endDate),
            'formatted_total' => core()->formatBasePrice($current),
            'progress'        => $this->getPercentageChange($previous, $current),
        ];
    }

    /**
     * Retrieves refunds
     *
     * @param  object  $seller
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     * @return array
     */
    public function getRefunds($seller, $startDate, $endDate): float
    {
        return $this->orderRepository
            ->resetModel()
            ->leftJoin('orders', 'marketplace_orders.order_id', '=', 'orders.id')
            ->whereIn('orders.channel_id', $this->channelIds)
            ->whereBetween('marketplace_orders.created_at', [$startDate, $endDate])
            ->where('marketplace_seller_id', $seller->id)
            ->sum(DB::raw('marketplace_orders.base_grand_total_refunded'));
    }

    /**
     * Returns previous refunds over time
     *
     * @param  object  $seller
     * @param  string  $period
     * @param  bool  $includeEmpty
     */
    public function getPreviousRefundsOverTime($seller, $period = 'auto', $includeEmpty = true): array
    {
        return $this->getRefundsOverTime($seller, $this->lastStartDate, $this->lastEndDate, $period, $includeEmpty);
    }

    /**
     * Returns current refunds over time
     *
     * @param  object  $seller
     * @param  string  $period
     * @param  bool  $includeEmpty
     */
    public function getCurrentRefundsOverTime($seller, $period = 'auto', $includeEmpty = true): array
    {
        return $this->getRefundsOverTime($seller, $this->startDate, $this->endDate, $period, $includeEmpty);
    }

    /**
     * Returns refunds over time
     *
     * @param  object  $seller
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     * @param  string  $period
     * @param  bool  $includeEmpty
     */
    public function getRefundsOverTime($seller, $startDate, $endDate, $period, $includeEmpty): array
    {
        return $this->getOverTimeStats(
            $seller,
            $startDate,
            $endDate,
            'SUM(base_grand_total_refunded)',
            $period
        );
    }

    /**
     * Retrieves tax collected and their progress.
     *
     * @param  object  $seller
     */
    public function getTaxCollectedProgress($seller): array
    {
        return [
            'previous'        => $previous = $this->getTaxCollected($seller, $this->lastStartDate, $this->lastEndDate),
            'current'         => $current = $this->getTaxCollected($seller, $this->startDate, $this->endDate),
            'formatted_total' => core()->formatBasePrice($current),
            'progress'        => $this->getPercentageChange($previous, $current),
        ];
    }

    /**
     * Retrieves tax collected
     *
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     * @return array
     */
    public function getTaxCollected($seller, $startDate, $endDate): float
    {
        return $this->orderRepository
            ->resetModel()
            ->leftJoin('orders', 'marketplace_orders.order_id', '=', 'orders.id')
            ->where('marketplace_orders.marketplace_seller_id', $seller->id)
            ->whereIn('orders.channel_id', $this->channelIds)
            ->whereBetween('marketplace_orders.created_at', [$startDate, $endDate])
            ->sum(DB::raw('marketplace_orders.base_tax_amount_invoiced - marketplace_orders.base_tax_amount_refunded'));
    }

    /**
     * Returns previous tax collected over time
     *
     * @param  object  $seller
     * @param  string  $period
     * @param  bool  $includeEmpty
     */
    public function getPreviousTaxCollectedOverTime($seller, $period = 'auto', $includeEmpty = true): array
    {
        return $this->getTaxCollectedOverTime($seller, $this->lastStartDate, $this->lastEndDate, $period, $includeEmpty);
    }

    /**
     * Returns current tax collected over time
     *
     * @param  object  $seller
     * @param  string  $period
     * @param  bool  $includeEmpty
     */
    public function getCurrentTaxCollectedOverTime($seller, $period = 'auto', $includeEmpty = true): array
    {
        return $this->getTaxCollectedOverTime($seller, $this->startDate, $this->endDate, $period, $includeEmpty);
    }

    /**
     * Returns tax collected over time
     *
     * @param  object  $seller
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     * @param  string  $period
     * @param  bool  $includeEmpty
     */
    public function getTaxCollectedOverTime($seller, $startDate, $endDate, $period, $includeEmpty): array
    {
        return $this->getOverTimeStats(
            $seller,
            $startDate,
            $endDate,
            'SUM(base_tax_amount_invoiced - base_tax_amount_refunded)',
            $period
        );
    }

    /**
     * Returns top tax categories
     *
     * @param  int  $limit
     */
    public function getTopTaxCategories($seller, $limit = null): Collection
    {
        return $this->orderItemRepository
            ->resetModel()
            ->leftJoin('order_items', 'marketplace_order_items.order_item_id', '=', 'order_items.id')
            ->leftJoin('marketplace_orders', 'marketplace_orders.id', '=', 'marketplace_order_items.marketplace_order_id')
            ->leftJoin('orders', 'marketplace_orders.order_id', '=', 'orders.id')
            ->leftJoin('tax_categories', 'order_items.tax_category_id', '=', 'tax_categories.id')
            ->select('tax_categories.id as tax_category_id', 'tax_categories.name')
            ->addSelect(DB::raw('SUM(order_items.base_tax_amount_invoiced - order_items.base_tax_amount_refunded) as total'))
            ->where('marketplace_orders.marketplace_seller_id', $seller->id)
            ->whereIn('orders.channel_id', $this->channelIds)
            ->whereBetween('order_items.created_at', [$this->startDate, $this->endDate])
            ->whereNotNull('tax_category_id')
            ->groupBy('tax_category_id')
            ->orderByDesc('total')
            ->limit($limit)
            ->get();
    }

    /**
     * Returns top payment methods
     *
     * @param  object  $seller
     * @param  int  $limit
     */
    public function getTopPaymentMethods($seller, $limit = null): object
    {
        return $this->orderRepository
            ->resetModel()
            ->leftJoin('orders', 'marketplace_orders.order_id', '=', 'orders.id')
            ->leftJoin('order_payment', 'orders.id', '=', 'order_payment.order_id')
            ->select('method', 'method_title as title')
            ->addSelect(DB::raw('COUNT(*) as total'))
            ->addSelect(DB::raw('SUM(marketplace_orders.base_grand_total) as base_total'))
            ->where('marketplace_orders.marketplace_seller_id', $seller->id)
            ->whereIn('orders.channel_id', $this->channelIds)
            ->whereBetween('orders.created_at', [$this->startDate, $this->endDate])
            ->groupBy('method')
            ->orderByDesc('total')
            ->limit($limit)
            ->get();
    }

    /**
     * Retrieves seller total payout progress.
     *
     * @param  object  $seller
     */
    public function getTotalPayoutProgress($seller): array
    {
        return [
            'previous'        => $this->getTotalPayout($seller, $this->lastStartDate, $this->lastEndDate),
            'current'         => $current = $this->getTotalPayout($seller, $this->startDate, $this->endDate),
            'formatted_total' => core()->formatBasePrice($current),
            'percent'         => $this->getAverageTotalPayout($seller, $this->startDate, $this->endDate),
        ];
    }

    /**
     * Retrieves seller remaining payout progress.
     *
     * @param  object  $seller
     */
    public function getRemainingPayoutProgress($seller): array
    {
        return [
            'previous'        => $this->getRemainingPayout($seller, $this->lastStartDate, $this->lastEndDate),
            'current'         => $current = $this->getRemainingPayout($seller, $this->startDate, $this->endDate),
            'formatted_total' => core()->formatBasePrice($current),
            'percent'         => $this->getAverageRemainingPayout($seller, $this->startDate, $this->endDate),
        ];
    }

    /**
     * Retrieves seller remaining payout.
     *
     * @param  object  $seller
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     */
    public function getTotalPayout($seller, $startDate, $endDate): ?float
    {
        return $this->transactionRepository
            ->where('marketplace_seller_id', $seller->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum(DB::raw('base_total'));
    }

    /**
     * Retrieves seller remaining payout.
     *
     * @param  object  $seller
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     */
    public function getRemainingPayout($seller, $startDate, $endDate): ?float
    {
        return $this->orderRepository
            ->where('marketplace_seller_id', $seller->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('seller_payout_status', 'requested')
            ->where('status', 'completed')
            ->sum(DB::raw('base_sub_total_invoiced - base_sub_total_refunded + base_tax_amount_invoiced - base_tax_amount_refunded - base_commission_invoiced'));
    }

    /**
     * Retrieves average total payout.
     *
     * @param  object  $seller
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     */
    public function getAverageTotalPayout($seller, $startDate, $endDate): ?float
    {
        if (! $this->getRemainingPayout($seller, $startDate, $endDate)
            || ! $this->getTotalPayout($seller, $startDate, $endDate)
        ) {
            return 0;
        }

        return $this->getTotalPayout($seller, $startDate, $endDate) * 100 /
        ($this->getTotalPayout($seller, $startDate, $endDate) + $this->getRemainingPayout($seller, $startDate, $endDate));
    }

    /**
     * Retrieves average remaining payout.
     *
     * @param  object  $seller
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     */
    public function getAverageRemainingPayout($seller, $startDate, $endDate): ?float
    {
        if (! $this->getRemainingPayout($seller, $startDate, $endDate)
            || ! $this->getTotalPayout($seller, $startDate, $endDate)
        ) {
            return 0;
        }

        return $this->getRemainingPayout($seller, $startDate, $endDate) * 100 /
        ($this->getTotalPayout($seller, $startDate, $endDate) + $this->getRemainingPayout($seller, $startDate, $endDate));
    }

    /**
     * Retrieves total unique cart users
     *
     * @param  object  $seller
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     */
    public function getTotalUniqueOrdersUsers($seller, $startDate, $endDate): int
    {
        return $this->orderRepository
            ->resetModel()
            ->leftJoin('orders', 'marketplace_orders.order_id', '=', 'orders.id')
            ->whereIn('orders.channel_id', $this->channelIds)
            ->where('marketplace_orders.marketplace_seller_id', $seller->id)
            ->whereBetween('marketplace_orders.created_at', [$startDate, $endDate])
            ->groupBy(DB::raw('CONCAT(customer_email, "-", customer_id)'))
            ->get()
            ->count();
    }

    /**
     * Returns over time stats.
     *
     * @param  object  $seller
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     * @param  string  $valueColumn
     * @param  string  $period
     */
    public function getOverTimeStats($seller, $startDate, $endDate, $valueColumn, $period = 'auto'): array
    {
        $config = $this->getTimeInterval($startDate, $endDate, $period);

        $groupColumn = $config['group_column'];

        $results = $this->orderRepository
            ->select(
                DB::raw("$groupColumn AS date"),
                DB::raw("$valueColumn AS total"),
                DB::raw('COUNT(*) AS count')
            )
            ->where('marketplace_seller_id', $seller->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->get();

        foreach ($config['intervals'] as $interval) {
            $total = $results->where('date', $interval['filter'])->first();

            $stats[] = [
                'label' => $interval['start'],
                'total' => $total?->total ?? 0,
                'count' => $total?->count ?? 0,
            ];
        }

        return $stats;
    }
}
