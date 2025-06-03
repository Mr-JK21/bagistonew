<?php

namespace Webkul\Marketplace\DataGrids\Seller;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;
use Webkul\Marketplace\Repositories\RoleRepository;

class UserDataGrid extends DataGrid
{
    /**
     * Prepare query builder.
     *
     * @return Builder
     */
    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('marketplace_sellers')
            ->leftJoin('mp_seller_flags', 'marketplace_sellers.id', '=', 'mp_seller_flags.marketplace_seller_id')
            ->leftJoin('marketplace_roles', 'marketplace_sellers.marketplace_role_id', '=', 'marketplace_roles.id')
            ->select(
                'marketplace_sellers.id',
                'marketplace_sellers.name as seller_name',
                'marketplace_sellers.email',
                'marketplace_sellers.phone',
                'marketplace_sellers.is_suspended',
                'marketplace_roles.name as role_name'
            )
            ->where('marketplace_sellers.parent_id', seller()->user()->id)
            ->groupBy('marketplace_sellers.id');

        $this->addFilter('seller_name', 'marketplace_sellers.name');
        $this->addFilter('role_name', 'marketplace_roles.name');

        return $queryBuilder;
    }

    /**
     * Prepare columns.
     *
     * @return void
     */
    public function prepareColumns()
    {
        $this->addColumn([
            'index'      => 'id',
            'label'      => trans('marketplace::app.seller.settings.users.index.datagrid.id'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'seller_name',
            'label'      => trans('marketplace::app.seller.settings.users.index.datagrid.name'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'email',
            'label'      => trans('marketplace::app.seller.settings.users.index.datagrid.email'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'              => 'is_suspended',
            'label'              => trans('marketplace::app.seller.settings.users.index.datagrid.status.title'),
            'type'               => 'string',
            'filterable'         => true,
            'filterable_type'    => 'dropdown',
            'filterable_options' => [
                [
                    'label' => trans('marketplace::app.seller.settings.users.index.datagrid.status.options.active'),
                    'value' => 0,
                ],
                [
                    'label' => trans('marketplace::app.seller.settings.users.index.datagrid.status.options.suspended'),
                    'value' => 1,
                ],
            ],
            'sortable'           => true,
            'searchable'         => false,
        ]);

        $this->addColumn([
            'index'      => 'phone',
            'label'      => trans('marketplace::app.seller.settings.users.index.datagrid.phone'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => false,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'              => 'role_name',
            'label'              => trans('marketplace::app.seller.settings.users.index.datagrid.permission'),
            'type'               => 'string',
            'searchable'         => true,
            'filterable'         => true,
            'filterable_type'    => 'dropdown',
            'filterable_options' => app(RoleRepository::class)->all([
                'name as label',
                'name as value',
            ])->toArray(),
            'sortable'           => true,
        ]);
    }

    /**
     * Prepare actions.
     *
     * @return void
     */
    public function prepareActions()
    {
        if (seller()->hasPermission('sellers.edit')) {
            $this->addAction([
                'icon'   => 'mp-pen-icon',
                'title'  => trans('marketplace::app.seller.settings.users.index.datagrid.edit'),
                'method' => 'GET',
                'url'    => function ($row) {
                    return route('seller.settings.users.edit', $row->id);
                },
            ]);
        }

        if (seller()->hasPermission('sellers.delete')) {
            $this->addAction([
                'icon'   => 'mp-delete-icon',
                'title'  => trans('marketplace::app.seller.settings.users.index.datagrid.delete'),
                'method' => 'DELETE',
                'url'    => function ($row) {
                    return route('seller.settings.users.delete', $row->id);
                },
            ]);
        }
    }
}
