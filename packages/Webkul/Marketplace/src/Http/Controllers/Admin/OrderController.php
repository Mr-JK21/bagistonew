<?php

namespace Webkul\Marketplace\Http\Controllers\Admin;

use Webkul\Marketplace\DataGrids\Admin\OrderDataGrid;

class OrderController extends Controller
{
    /**
     * Method to populate the seller order page which will be populated.
     *
     * @return mixed
     */
    public function index()
    {
        if (request()->ajax()) {
            return datagrid(OrderDataGrid::class)->process();
        }

        return view('marketplace::admin.orders.index');
    }
}
