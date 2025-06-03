<?php

namespace Webkul\Marketplace\Http\Controllers\Seller\Reporting;

class SaleController extends Controller
{
    /**
     * Request param functions.
     *
     * @var array
     */
    protected $typeFunctions = [
        'total-sales'           => 'getTotalSalesStats',
        'total-commission-paid' => 'getTotalCommissionPaidStats',
        'average-sales'         => 'getAverageSalesStats',
        'total-orders'          => 'getTotalOrdersStats',
        'purchase-funnel'       => 'getPurchaseFunnelStats',
        'abandoned-carts'       => 'getAbandonedCartsStats',
        'refunds'               => 'getRefundsStats',
        'tax-collected'         => 'getTaxCollectedStats',
        'top-payment-methods'   => 'getTopPaymentMethods',
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('marketplace::seller.reporting.sales.index')->with([
            'startDate' => $this->reportingHelper->getStartDate(),
            'endDate'   => $this->reportingHelper->getEndDate(),
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function view()
    {
        return view('marketplace::seller.reporting.view')->with([
            'entity'    => 'sales',
            'startDate' => $this->reportingHelper->getStartDate(),
            'endDate'   => $this->reportingHelper->getEndDate(),
        ]);
    }
}
