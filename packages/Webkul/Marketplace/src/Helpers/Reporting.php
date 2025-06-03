<?php

namespace Webkul\Marketplace\Helpers;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Carbon;
use Webkul\Marketplace\Helpers\Reporting\Cart;
use Webkul\Marketplace\Helpers\Reporting\Customer;
use Webkul\Marketplace\Helpers\Reporting\Product;
use Webkul\Marketplace\Helpers\Reporting\Sale;
use Webkul\Marketplace\Helpers\Reporting\Visitor;
use Webkul\Product\Models\Product as ProductModel;

class Reporting
{
    /**
     * Create a controller instance.
     *
     * @return void
     */
    public function __construct(
        protected Cart $cartReporting,
        protected Sale $saleReporting,
        protected Product $productReporting,
        protected Customer $customerReporting,
        protected Visitor $visitorReporting
    ) {}

    /**
     * Returns the sales statistics.
     *
     * @param  \Webkul\Seller\Contracts\Seller  $seller
     * @param  string  $type
     */
    public function getTotalSalesStats($seller, $type = 'graph'): array
    {
        if ($type == 'table') {
            $records = collect($this->saleReporting->getCurrentTotalSalesOverTime(
                $seller,
                request()->query('period') ?? 'day')
            );

            $records = $records->map(function ($record) {
                $record['formatted_total'] = core()->formatBasePrice($record['total']);

                return $record;
            });

            return [
                'columns' => [
                    [
                        'key'   => 'label',
                        'label' => trans('marketplace::app.seller.reporting.sales.index.interval'),
                    ], [
                        'key'   => 'count',
                        'label' => trans('marketplace::app.seller.reporting.sales.index.orders'),
                    ], [
                        'key'   => 'formatted_total',
                        'label' => trans('marketplace::app.seller.reporting.sales.index.total'),
                    ],
                ],

                'records'  => $records,
            ];
        }

        return [
            'sales'     => $this->saleReporting->getTotalSalesProgress($seller),

            'over_time' => [
                'previous' => $this->saleReporting->getPreviousTotalSalesOverTime($seller),
                'current'  => $this->saleReporting->getCurrentTotalSalesOverTime($seller),
            ],
        ];
    }

    /**
     * Returns the commission statistics.
     *
     * @param  \Webkul\Seller\Contracts\Seller  $seller
     * @param  string  $type
     */
    public function getTotalCommissionPaidStats($seller, $type = 'graph'): array
    {
        if ($type == 'table') {
            $records = collect($this->saleReporting->getCurrentTotalCommissionPaidOverTime(
                $seller,
                request()->query('period') ?? 'day')
            );

            $records = $records->map(function ($record) {
                $record['formatted_commission'] = core()->formatBasePrice($record['total']);

                return $record;
            });

            return [
                'columns' => [
                    [
                        'key'   => 'label',
                        'label' => trans('marketplace::app.seller.reporting.sales.index.interval'),
                    ], [
                        'key'   => 'count',
                        'label' => trans('marketplace::app.seller.reporting.sales.index.orders'),
                    ], [
                        'key'   => 'formatted_commission',
                        'label' => trans('marketplace::app.seller.reporting.sales.index.commission'),
                    ],
                ],

                'records'  => $records,
            ];
        }

        return [
            'commission'     => $this->saleReporting->getTotalCommissionPaidProgress($seller),

            'over_time' => [
                'previous' => $this->saleReporting->getPreviousTotalCommissionPaidOverTime($seller),
                'current'  => $this->saleReporting->getCurrentTotalCommissionPaidOverTime($seller),
            ],
        ];
    }

    /**
     * Returns the sales statistics.
     *
     * @param  \Webkul\Seller\Contracts\Seller  $seller
     * @param  string  $type
     */
    public function getAverageSalesStats($seller, $type = 'graph'): array
    {
        if ($type == 'table') {
            $records = collect($this->saleReporting->getCurrentAverageSalesOverTime(
                $seller,
                request()->query('period') ?? 'day')
            );

            $records = $records->map(function ($record) {
                $record['formatted_total'] = core()->formatBasePrice($record['total']);

                return $record;
            });

            return [
                'columns' => [
                    [
                        'key'   => 'label',
                        'label' => trans('marketplace::app.seller.reporting.sales.index.interval'),
                    ], [
                        'key'   => 'count',
                        'label' => trans('marketplace::app.seller.reporting.sales.index.orders'),
                    ], [
                        'key'   => 'formatted_total',
                        'label' => trans('marketplace::app.seller.reporting.sales.index.total'),
                    ],
                ],

                'records'  => $records,
            ];
        }

        return [
            'sales'     => $this->saleReporting->getAverageSalesProgress($seller),

            'over_time' => [
                'previous' => $this->saleReporting->getPreviousAverageSalesOverTime($seller),
                'current'  => $this->saleReporting->getCurrentAverageSalesOverTime($seller),
            ],
        ];
    }

    /**
     * Returns the total orders statistics.
     *
     * @param  \Webkul\Seller\Contracts\Seller  $seller
     * @param  string  $type
     */
    public function getTotalOrdersStats($seller, $type = 'graph'): array
    {
        if ($type == 'table') {
            return [
                'columns' => [
                    [
                        'key'   => 'label',
                        'label' => trans('marketplace::app.seller.reporting.sales.index.interval'),
                    ], [
                        'key'   => 'count',
                        'label' => trans('marketplace::app.seller.reporting.sales.index.orders'),
                    ],
                ],

                'records'  => $this->saleReporting->getCurrentTotalOrdersOverTime(
                    $seller,
                    request()->query('period') ?? 'day'
                ),
            ];
        }

        return [
            'orders'    => $this->saleReporting->getTotalOrdersProgress($seller),

            'over_time' => [
                'previous' => $this->saleReporting->getPreviousTotalOrdersOverTime($seller),
                'current'  => $this->saleReporting->getCurrentTotalOrdersOverTime($seller),
            ],
        ];
    }

    /**
     * Returns the purchase funnel statistics.
     *
     * @param  \Webkul\Seller\Contracts\Seller  $seller
     */
    public function getPurchaseFunnelStats($seller): array
    {
        $startDate = $this->visitorReporting->getStartDate();

        $endDate = $this->visitorReporting->getEndDate();

        return [
            'visitors' => [
                'total'    => $totalVisitors = $this->visitorReporting->getTotalUniqueVisitors($seller, $startDate, $endDate),
                'progress' => $totalVisitors ? 100 : 0,
            ],

            'product_visitors' => [
                'total'    => $totalProductVisitors = $this->visitorReporting->getTotalUniqueVisitors($seller, $startDate, $endDate, ProductModel::class),
                'progress' => round($totalVisitors > 0 ? ($totalProductVisitors * 100) / $totalVisitors : 0, 1),
            ],

            'carts' => [
                'total'    => $totalCarts = $this->cartReporting->getTotalUniqueCartsUsers($seller, $startDate, $endDate),
                'progress' => round(min($totalVisitors > 0 ? ($totalCarts * 100) / $totalVisitors : 0, 100), 1),
            ],

            'orders' => [
                'total'    => $totalOrders = $this->saleReporting->getTotalUniqueOrdersUsers($seller, $startDate, $endDate),
                'progress' => round(min($totalVisitors > 0 ? ($totalOrders * 100) / $totalVisitors : 0, 100), 1),
            ],
        ];
    }

    /**
     * Returns the abandoned carts statistics.
     *
     * @param  \Webkul\Seller\Contracts\Seller  $seller
     * @param  string  $type
     */
    public function getAbandonedCartsStats($seller, $type = 'graph'): array
    {
        if ($type == 'table') {
            $records = $this->cartReporting->getAbandonedCartProducts($seller);

            return [
                'columns' => [
                    [
                        'key'   => 'id',
                        'label' => trans('marketplace::app.seller.reporting.sales.index.id'),
                    ], [
                        'key'   => 'name',
                        'label' => trans('marketplace::app.seller.reporting.sales.index.name'),
                    ], [
                        'key'   => 'count',
                        'label' => trans('marketplace::app.seller.reporting.sales.index.count'),
                    ],
                ],

                'records'  => $records,
            ];
        }

        $totalAbandonedProducts = $this->cartReporting->getTotalAbandonedCartProducts($seller);

        $products = $this->cartReporting->getAbandonedCartProducts($seller, 5);

        $products->map(function ($product) use ($totalAbandonedProducts) {
            if (! $totalAbandonedProducts) {
                $product->progress = 0;
            } else {
                $product->progress = ($product->count * 100) / $totalAbandonedProducts;
            }

            return $product;
        });

        return [
            'sales'    => $this->cartReporting->getTotalAbandonedSalesProgress($seller),
            'carts'    => $this->cartReporting->getTotalAbandonedCartsProgress($seller),
            'rate'     => $this->cartReporting->getTotalAbandonedCartRateProgress($seller),
            'products' => $products,
        ];
    }

    /**
     * Returns the sales statistics.
     *
     * @param  \Webkul\Seller\Contracts\Seller  $seller
     * @param  string  $type
     */
    public function getRefundsStats($seller, $type = 'graph'): array
    {
        if ($type == 'table') {
            $records = collect($this->saleReporting->getCurrentRefundsOverTime(
                $seller,
                request()->query('period') ?? 'day')
            );

            $records = $records->map(function ($record) {
                $record['formatted_total'] = core()->formatBasePrice($record['total']);

                return $record;
            });

            return [
                'columns' => [
                    [
                        'key'   => 'label',
                        'label' => trans('marketplace::app.seller.reporting.sales.index.interval'),
                    ], [
                        'key'   => 'count',
                        'label' => trans('marketplace::app.seller.reporting.sales.index.orders'),
                    ], [
                        'key'   => 'formatted_total',
                        'label' => trans('marketplace::app.seller.reporting.sales.index.total'),
                    ],
                ],

                'records'  => $records,
            ];
        }

        return [
            'refunds'   => $this->saleReporting->getRefundsProgress($seller),

            'over_time' => [
                'previous' => $this->saleReporting->getPreviousRefundsOverTime($seller),
                'current'  => $this->saleReporting->getCurrentRefundsOverTime($seller),
            ],
        ];
    }

    /**
     * Returns the tax collected statistics.
     *
     * @param  \Webkul\Seller\Contracts\Seller  $seller
     * @param  string  $type
     */
    public function getTaxCollectedStats($seller, $type = 'graph'): array
    {
        if ($type == 'table') {
            $records = collect($this->saleReporting->getCurrentTaxCollectedOverTime(
                $seller,
                request()->query('period') ?? 'day')
            );

            $records = $records->map(function ($record) {
                $record['formatted_total'] = core()->formatBasePrice($record['total']);

                return $record;
            });

            return [
                'columns' => [
                    [
                        'key'   => 'label',
                        'label' => trans('marketplace::app.seller.reporting.sales.index.interval'),
                    ], [
                        'key'   => 'count',
                        'label' => trans('marketplace::app.seller.reporting.sales.index.orders'),
                    ], [
                        'key'   => 'formatted_total',
                        'label' => trans('marketplace::app.seller.reporting.sales.index.total'),
                    ],
                ],

                'records'  => $records,
            ];
        }

        $taxCollected = $this->saleReporting->getTaxCollectedProgress($seller);

        $taxCategories = $this->saleReporting->getTopTaxCategories($seller, 5);

        $taxCategories->map(function ($taxCategory) use ($taxCollected) {
            if (! $taxCollected['current']) {
                $taxCategory->progress = 0;
            } else {
                $taxCategory->progress = ($taxCategory->total * 100) / $taxCollected['current'];
            }

            $taxCategory->formatted_total = core()->formatBasePrice($taxCategory->total);

            return $taxCategory;
        });

        return [
            'tax_collected'  => $taxCollected,
            'top_categories' => $taxCategories,

            'over_time'      => [
                'previous' => $this->saleReporting->getPreviousTaxCollectedOverTime($seller),
                'current'  => $this->saleReporting->getCurrentTaxCollectedOverTime($seller),
            ],
        ];
    }

    /**
     * Returns the shipping collected statistics.
     *
     * @param  \Webkul\Seller\Contracts\Seller  $seller
     * @param  string  $type
     */
    public function getTopPaymentMethods($seller, $type = 'graph'): EloquentCollection|array
    {
        if ($type == 'table') {
            $records = collect($this->saleReporting->getTopPaymentMethods($seller));

            $records = $records->map(function ($paymentMethod) {
                $paymentMethod->formatted_total = core()->formatBasePrice($paymentMethod->base_total);

                $paymentMethod->title = $paymentMethod->title ?? core()->getConfigData('sales.payment_methods.'.$paymentMethod->method.'.title');

                return $paymentMethod;
            });

            return [
                'columns' => [
                    [
                        'key'   => 'title',
                        'label' => trans('marketplace::app.seller.reporting.sales.index.payment-method'),
                    ], [
                        'key'   => 'total',
                        'label' => trans('marketplace::app.seller.reporting.sales.index.orders'),
                    ], [
                        'key'   => 'formatted_total',
                        'label' => trans('marketplace::app.seller.reporting.sales.index.total'),
                    ],
                ],

                'records'  => $records,
            ];
        }

        $totalOrders = $this->saleReporting->getTotalOrdersProgress($seller);

        $paymentMethods = $this->saleReporting->getTopPaymentMethods($seller, 5);

        $paymentMethods->map(function ($paymentMethod) use ($totalOrders) {
            if (! $totalOrders['current']) {
                $paymentMethod->progress = 0;
            } else {
                $paymentMethod->progress = ($paymentMethod->total * 100) / $totalOrders['current'];
            }

            $paymentMethod->formatted_total = core()->formatBasePrice($paymentMethod->base_total);

            $paymentMethod->title = $paymentMethod->title ?? core()->getConfigData('sales.payment_methods.'.$paymentMethod->method.'.title');
        });

        return $paymentMethods;
    }

    /**
     * Returns the total customers statistics.
     *
     * @param  \Webkul\Seller\Contracts\Seller  $seller
     * @param  string  $type
     */
    public function getTotalCustomersStats($seller, $type = 'graph'): array
    {
        if ($type == 'table') {
            return [
                'columns' => [
                    [
                        'key'   => 'label',
                        'label' => trans('marketplace::app.seller.reporting.customers.index.interval'),
                    ], [
                        'key'   => 'total',
                        'label' => trans('marketplace::app.seller.reporting.customers.index.customers'),
                    ],
                ],

                'records'  => $this->customerReporting->getCurrentTotalCustomersOverTime(
                    $seller,
                    request()->query('period') ?? 'day'
                ),
            ];
        }

        return [
            'customers' => $this->customerReporting->getTotalCustomersProgress($seller),

            'over_time' => [
                'previous' => $this->customerReporting->getPreviousTotalCustomersOverTime($seller),
                'current'  => $this->customerReporting->getCurrentTotalCustomersOverTime($seller),
            ],
        ];
    }

    /**
     * Returns the total customers statistics.
     *
     * @param  \Webkul\Seller\Contracts\Seller  $seller
     */
    public function getCustomersTrafficStats($seller): array
    {
        return [
            'total'     => $this->visitorReporting->getTotalVisitorsProgress($seller),
            'unique'    => $this->visitorReporting->getTotalUniqueVisitorsProgress($seller),

            'over_time' => [
                'previous' => $this->visitorReporting->getPreviousTotalVisitorsOverWeek($seller),
                'current'  => $this->visitorReporting->getCurrentTotalVisitorsOverWeek($seller),
            ],
        ];
    }

    /**
     * Returns the customers with most sales
     *
     * @param  \Webkul\Seller\Contracts\Seller  $seller
     * @param  string  $type
     */
    public function getCustomersWithMostSales($seller, $type = 'graph'): EloquentCollection|array
    {
        if ($type == 'table') {
            $records = collect($this->customerReporting->getCustomersWithMostSales($seller, 6));

            $records = $records->map(function ($record) {
                $record['formatted_total'] = core()->formatBasePrice($record['total']);

                return $record;
            });

            return [
                'columns' => [
                    [
                        'key'   => 'full_name',
                        'label' => trans('marketplace::app.seller.reporting.customers.index.name'),
                    ], [
                        'key'   => 'email',
                        'label' => trans('marketplace::app.seller.reporting.customers.index.email'),
                    ], [
                        'key'   => 'formatted_total',
                        'label' => trans('marketplace::app.seller.reporting.customers.index.total'),
                    ],
                ],

                'records'  => $records,
            ];
        }

        $totalSales = $this->saleReporting->getTotalSalesProgress($seller);

        $customers = $this->customerReporting->getCustomersWithMostSales($seller, 5);

        $customers->map(function ($customer) use ($totalSales) {
            if (! $totalSales['current']) {
                $customer->progress = 0;
            } else {
                $customer->progress = ($customer->total * 100) / $totalSales['current'];
            }

            $customer->formatted_total = core()->formatBasePrice($customer->total);
        });

        return $customers;
    }

    /**
     * Returns the customers with most orders
     *
     * @param  \Webkul\Seller\Contracts\Seller  $seller
     * @param  string  $type
     */
    public function getCustomersWithMostOrders($seller, $type = 'graph'): EloquentCollection|array
    {
        if ($type == 'table') {
            $records = $this->customerReporting->getCustomersWithMostOrders($seller);

            return [
                'columns' => [
                    [
                        'key'   => 'full_name',
                        'label' => trans('marketplace::app.seller.reporting.customers.index.name'),
                    ], [
                        'key'   => 'email',
                        'label' => trans('marketplace::app.seller.reporting.customers.index.email'),
                    ], [
                        'key'   => 'orders',
                        'label' => trans('marketplace::app.seller.reporting.customers.index.orders'),
                    ],
                ],

                'records'  => $records,
            ];
        }

        $totalOrders = $this->saleReporting->getTotalOrdersProgress($seller);

        $customers = $this->customerReporting->getCustomersWithMostOrders($seller, 5);

        $customers->map(function ($customer) use ($totalOrders) {
            if (! $totalOrders['current']) {
                $customer->progress = 0;
            } else {
                $customer->progress = ($customer->orders * 100) / $totalOrders['current'];
            }
        });

        return $customers;
    }

    /**
     * Returns the customers with most reviews
     *
     * @param  \Webkul\Seller\Contracts\Seller  $seller
     * @param  string  $type
     */
    public function getCustomersWithMostReviews($seller, $type = 'graph'): EloquentCollection|array
    {
        if ($type == 'table') {
            $records = $this->customerReporting->getCustomersWithMostReviews($seller);

            return [
                'columns' => [
                    [
                        'key'   => 'full_name',
                        'label' => trans('marketplace::app.seller.reporting.customers.index.name'),
                    ], [
                        'key'   => 'email',
                        'label' => trans('marketplace::app.seller.reporting.customers.index.email'),
                    ], [
                        'key'   => 'reviews',
                        'label' => trans('marketplace::app.seller.reporting.customers.index.reviews'),
                    ],
                ],

                'records'  => $records,
            ];
        }

        $totalReviews = $this->customerReporting->getTotalReviewsProgress($seller);

        $customers = $this->customerReporting->getCustomersWithMostReviews($seller, 5);

        $customers->map(function ($customer) use ($totalReviews) {
            if (! $totalReviews['current']) {
                $customer->progress = 0;
            } else {
                $customer->progress = ($customer->reviews * 100) / $totalReviews['current'];
            }
        });

        return $customers;
    }

    /**
     * Returns the top customers
     *
     * @param  \Webkul\Seller\Contracts\Seller  $seller
     * @param  string  $type
     */
    public function getTopCustomerGroups($seller, $type = 'graph'): EloquentCollection|array
    {
        if ($type == 'table') {
            $records = $this->customerReporting->getGroupsWithMostCustomers($seller);

            return [
                'columns' => [
                    [
                        'key'   => 'group_name',
                        'label' => trans('marketplace::app.seller.reporting.customers.index.name'),
                    ], [
                        'key'   => 'total',
                        'label' => trans('marketplace::app.seller.reporting.customers.index.customers'),
                    ],
                ],

                'records'  => $records,
            ];
        }

        $totalCustomers = $this->customerReporting->getTotalCustomersProgress($seller);

        $groups = $this->customerReporting->getGroupsWithMostCustomers($seller, 5);

        $groups->map(function ($group) use ($totalCustomers) {
            if (! $totalCustomers['current']) {
                $group->progress = 0;
            } else {
                $group->progress = ($group->total * 100) / $totalCustomers['current'];
            }
        });

        return $groups;
    }

    /**
     * Returns the total sold quantities statistics.
     *
     * @param  \Webkul\Seller\Contracts\Seller  $seller
     * @param  string  $type
     */
    public function getTotalSoldQuantitiesStats($seller, $type = 'graph'): array
    {
        if ($type == 'table') {
            return [
                'columns' => [
                    [
                        'key'   => 'label',
                        'label' => trans('marketplace::app.seller.reporting.products.index.interval'),
                    ], [
                        'key'   => 'total',
                        'label' => trans('marketplace::app.seller.reporting.products.index.quantities'),
                    ],
                ],

                'records'  => $this->productReporting->getCurrentTotalSoldQuantitiesOverTime(
                    $seller,
                    request()->query('period') ?? 'day'
                ),
            ];
        }

        return [
            'quantities' => $this->productReporting->getTotalSoldQuantitiesProgress($seller),

            'over_time'  => [
                'previous' => $this->productReporting->getPreviousTotalSoldQuantitiesOverTime($seller),
                'current'  => $this->productReporting->getCurrentTotalSoldQuantitiesOverTime($seller),
            ],
        ];
    }

    /**
     * Returns the total products added to wishlist statistics.
     *
     * @param  \Webkul\Seller\Contracts\Seller  $seller
     * @param  string  $type
     */
    public function getTotalProductsAddedToWishlistStats($seller, $type = 'graph'): array
    {
        if ($type == 'table') {
            return [
                'columns' => [
                    [
                        'key'   => 'label',
                        'label' => trans('marketplace::app.seller.reporting.products.index.interval'),
                    ], [
                        'key'   => 'total',
                        'label' => trans('marketplace::app.seller.reporting.products.index.total'),
                    ],
                ],

                'records'  => $this->productReporting->getCurrentTotalProductsAddedToWishlistOverTime(
                    $seller,
                    request()->query('period') ?? 'day'
                ),
            ];
        }

        return [
            'wishlist'  => $this->productReporting->getTotalProductsAddedToWishlistProgress($seller),

            'over_time' => [
                'previous' => $this->productReporting->getPreviousTotalProductsAddedToWishlistOverTime($seller),
                'current'  => $this->productReporting->getCurrentTotalProductsAddedToWishlistOverTime($seller),
            ],
        ];
    }

    /**
     * Returns top selling products by revenue statistics.
     *
     * @param  \Webkul\Seller\Contracts\Seller  $seller
     * @param  string  $type
     */
    public function getTopSellingProductsByRevenue($seller, $type = 'graph'): array
    {
        if ($type == 'table') {
            $records = collect($this->productReporting->getTopSellingProductsByRevenue($seller));

            return [
                'columns' => [
                    [
                        'key'   => 'id',
                        'label' => trans('marketplace::app.seller.reporting.products.index.id'),
                    ], [
                        'key'   => 'name',
                        'label' => trans('marketplace::app.seller.reporting.products.index.name'),
                    ], [
                        'key'   => 'formatted_price',
                        'label' => trans('marketplace::app.seller.reporting.products.index.price'),
                    ], [
                        'key'   => 'formatted_revenue',
                        'label' => trans('marketplace::app.seller.reporting.products.index.revenue'),
                    ],
                ],

                'records'  => $records,
            ];
        }

        $totalSales = $this->saleReporting->getSubTotalSalesProgress($seller);

        $products = $this->productReporting->getTopSellingProductsByRevenue($seller, 5);

        $products = $products->map(function ($product) use ($totalSales) {
            if (! $totalSales['current']) {
                $product['progress'] = 0;
            } else {
                $product['progress'] = ($product['revenue'] * 100) / $totalSales['current'];
            }

            $product['formatted_revenue'] = core()->formatBasePrice($product['revenue']);

            return $product;
        });

        return $products->toArray();
    }

    /**
     * Returns top selling products by quantity statistics.
     *
     * @param  \Webkul\Seller\Contracts\Seller  $seller
     * @param  string  $type
     */
    public function getTopSellingProductsByQuantity($seller, $type = 'graph'): array
    {
        if ($type == 'table') {
            $records = $this->productReporting->getTopSellingProductsByQuantity($seller);

            return [
                'columns' => [
                    [
                        'key'   => 'id',
                        'label' => trans('marketplace::app.seller.reporting.products.index.id'),
                    ], [
                        'key'   => 'name',
                        'label' => trans('marketplace::app.seller.reporting.products.index.name'),
                    ], [
                        'key'   => 'total_qty_ordered',
                        'label' => trans('marketplace::app.seller.reporting.products.index.quantities'),
                    ],
                ],

                'records'  => $records,
            ];
        }

        $totalSoldQuantities = $this->productReporting->getTotalSoldQuantitiesProgress($seller);

        $products = $this->productReporting->getTopSellingProductsByQuantity($seller, 5);

        $products = $products->map(function ($product) use ($totalSoldQuantities) {
            if (! $totalSoldQuantities['current']) {
                $product['progress'] = 0;
            } else {
                $product['progress'] = ($product['total_qty_ordered'] * 100) / $totalSoldQuantities['current'];
            }

            return $product;
        });

        return $products->toArray();
    }

    /**
     * Returns the products with most reviews
     *
     * @param  \Webkul\Seller\Contracts\Seller  $seller
     * @param  string  $type
     */
    public function getProductsWithMostReviews($seller, $type = 'graph'): EloquentCollection|array
    {
        if ($type == 'table') {
            $records = $this->productReporting->getProductsWithMostReviews($seller);

            return [
                'columns' => [
                    [
                        'key'   => 'product_id',
                        'label' => trans('marketplace::app.seller.reporting.products.index.id'),
                    ], [
                        'key'   => 'product_name',
                        'label' => trans('marketplace::app.seller.reporting.products.index.name'),
                    ], [
                        'key'   => 'reviews',
                        'label' => trans('marketplace::app.seller.reporting.products.index.reviews'),
                    ],
                ],

                'records'  => $records,
            ];
        }

        $totalReviews = $this->productReporting->getTotalReviewsProgress($seller);

        $products = $this->productReporting->getProductsWithMostReviews($seller, 5);

        $products->map(function ($product) use ($totalReviews) {
            if (! $totalReviews['current']) {
                $product->progress = 0;
            } else {
                $product->progress = ($product->reviews * 100) / $totalReviews['current'];
            }
        });

        return $products;
    }

    /**
     * Returns the products with most visits
     *
     * @param  \Webkul\Seller\Contracts\Seller  $seller
     * @param  string  $type
     */
    public function getProductsWithMostVisits($seller, $type = 'graph'): EloquentCollection|array
    {
        if ($type == 'table') {
            $records = $this->visitorReporting->getVisitableWithMostVisits($seller, ProductModel::class);

            return [
                'columns' => [
                    [
                        'key'   => 'visitable_id',
                        'label' => trans('marketplace::app.seller.reporting.products.index.id'),
                    ], [
                        'key'   => 'name',
                        'label' => trans('marketplace::app.seller.reporting.products.index.name'),
                    ], [
                        'key'   => 'visits',
                        'label' => trans('marketplace::app.seller.reporting.products.index.visits'),
                    ],
                ],

                'records'  => $records,
            ];
        }

        $totalVisits = $this->visitorReporting->getTotalVisitorsProgress($seller, ProductModel::class);

        $products = $this->visitorReporting->getVisitableWithMostVisits($seller, ProductModel::class, 5);

        $products->map(function ($product) use ($totalVisits) {
            if (! $totalVisits['current']) {
                $product->progress = 0;
            } else {
                $product->progress = ($product->visits * 100) / $totalVisits['current'];
            }
        });

        return $products;
    }

    /**
     * Returns date range
     */
    public function getDateRange(): array
    {
        return [
            'previous' => $this->saleReporting->getLastStartDate()->format('d M Y').' - '.$this->saleReporting->getLastEndDate()->format('d M Y'),
            'current'  => $this->saleReporting->getStartDate()->format('d M Y').' - '.$this->saleReporting->getEndDate()->format('d M Y'),
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
}
