<?php

namespace Webkul\Marketplace\DataGrids\Admin;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;
use Webkul\Marketplace\Enums\FlagReasonType;
use Webkul\Marketplace\Enums\Status;

class FlagReasonDataGrid extends DataGrid
{
    /**
     * Prepare query builder.
     */
    public function prepareQueryBuilder(): Builder
    {
        return DB::table('mp_flag_reasons');
    }

    /**
     * Prepare columns.
     *
     * @return void
     */
    public function prepareColumns()
    {
        $this->addColumn([
            'index'           => 'id',
            'label'           => trans('marketplace::app.admin.flag-reasons.index.datagrid.id'),
            'type'            => 'integer',
            'filterable'      => true,
            'filterable_type' => 'number',
            'searchable'      => true,
            'sortable'        => true,
        ]);

        $this->addColumn([
            'index'      => 'admin_name',
            'label'      => trans('marketplace::app.admin.flag-reasons.index.datagrid.name'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'              => 'type',
            'label'              => trans('marketplace::app.admin.flag-reasons.index.datagrid.type.title'),
            'type'               => 'string',
            'filterable'         => true,
            'filterable_type'    => 'dropdown',
            'filterable_options' => [
                [
                    'label'  => trans('marketplace::app.admin.flag-reasons.index.datagrid.type.product'),
                    'value'  => FlagReasonType::PRODUCT->value,
                ],
                [
                    'label'  => trans('marketplace::app.admin.flag-reasons.index.datagrid.type.seller'),
                    'value'  => FlagReasonType::SELLER->value,
                ],
            ],
            'searchable' => true,
            'sortable'   => true,
            'closure'    => function ($row) {
                if ($row->type == FlagReasonType::PRODUCT->value) {
                    return trans('marketplace::app.admin.flag-reasons.index.datagrid.type.product');
                }

                return trans('marketplace::app.admin.flag-reasons.index.datagrid.type.seller');
            },
        ]);

        $this->addColumn([
            'index'              => 'status',
            'label'              => trans('marketplace::app.admin.flag-reasons.index.datagrid.status.title'),
            'type'               => 'integer',
            'filterable'         => true,
            'filterable_type'    => 'dropdown',
            'filterable_options' => [
                [
                    'label'  => trans('marketplace::app.admin.flag-reasons.index.datagrid.status.active'),
                    'value'  => Status::ACTIVE->value,
                ],
                [
                    'label'  => trans('marketplace::app.admin.flag-reasons.index.datagrid.status.disable'),
                    'value'  => Status::DISABLE->value,
                ],
            ],
            'searchable' => true,
            'sortable'   => true,
            'closure'    => function ($row) {
                if ($row->status == Status::ACTIVE->value) {
                    return '<span class="label-active">'.trans('marketplace::app.admin.flag-reasons.index.datagrid.status.active').'</span>';
                }

                return '<span class="label-info">'.trans('marketplace::app.admin.flag-reasons.index.datagrid.status.disable').'</span>';
            },
        ]);
    }

    /**
     * Prepare actions.
     *
     * @return void
     */
    public function prepareActions()
    {
        if (bouncer()->hasPermission('marketplace.flag-reasons.edit')) {
            $this->addAction([
                'icon'   => 'icon-edit',
                'title'  => trans('marketplace::app.admin.flag-reasons.index.datagrid.edit'),
                'method' => 'GET',
                'url'    => function ($row) {
                    return route('admin.marketplace.flag_reasons.edit', $row->id);
                },
            ]);
        }

        if (bouncer()->hasPermission('marketplace.flag-reasons.delete')) {
            $this->addAction([
                'icon'   => 'icon-delete',
                'title'  => trans('marketplace::app.admin.flag-reasons.index.datagrid.delete'),
                'method' => 'DELETE',
                'url'    => function ($row) {
                    return route('admin.marketplace.flag_reasons.delete', $row->id);
                },
            ]);
        }
    }

    /**
     * Prepare mass actions.
     *
     * @return void
     */
    public function prepareMassActions()
    {
        if (bouncer()->hasPermission('marketplace.flag-reasons.edit')) {
            $this->addMassAction([
                'title'   => trans('marketplace::app.admin.flag-reasons.index.datagrid.update-status'),
                'url'     => route('admin.marketplace.flag_reasons.mass_update'),
                'method'  => 'POST',
                'options' => [
                    [
                        'label' => trans('marketplace::app.admin.flag-reasons.index.datagrid.status.active'),
                        'value' => Status::ACTIVE->value,
                    ],
                    [
                        'label' => trans('marketplace::app.admin.flag-reasons.index.datagrid.status.disable'),
                        'value' => Status::DISABLE->value,
                    ],
                ],
            ]);
        }

        if (bouncer()->hasPermission('marketplace.flag-reasons.delete')) {
            $this->addMassAction([
                'title'  => trans('marketplace::app.admin.flag-reasons.index.datagrid.delete'),
                'url'    => route('admin.marketplace.flag_reasons.mass_delete'),
                'method' => 'POST',
            ]);
        }
    }
}
