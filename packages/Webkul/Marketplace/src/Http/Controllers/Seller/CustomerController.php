<?php

namespace Webkul\Marketplace\Http\Controllers\Seller;

use Webkul\Marketplace\DataGrids\Seller\CustomerDataGrid;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return mixed
     */
    public function index()
    {
        if (request()->ajax()) {
            return datagrid(CustomerDataGrid::class)->process();
        }

        return view('marketplace::seller.customers.index');
    }
}
