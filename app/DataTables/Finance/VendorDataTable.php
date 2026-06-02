<?php

namespace App\DataTables\Finance;

use App\Models\Finance\Vendor;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class VendorDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('active', function ($vendor) {
                $status = $vendor->active ? 'Active' : 'Inactive';
                $color = $vendor->active ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800';
                return '<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium ' . $color . '">' . $status . '</span>';
            })
            ->addColumn('action', 'finance.datatables.actions.vendors')
            ->setRowId('id')
            ->rawColumns(['active', 'action']);
    }

    public function query(Vendor $model): QueryBuilder
    {
        return $model->newQuery();
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('vendors-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(0)
            ->selectStyleSingle()
            ->parameters([
                'drawCallback' => 'function() { if(window.lucide) window.lucide.createIcons({ icons: window.lucide.icons }); }',
                'responsive' => true,
                'language' => [
                    'search' => '',
                    'searchPlaceholder' => 'Search vendors…',
                ],
            ]);
    }

    public function getColumns(): array
    {
        return [
            Column::make('name')->title('Name'),
            Column::make('contact_person')->title('Contact Person'),
            Column::make('email')->title('Email'),
            Column::make('phone')->title('Phone'),
            Column::make('active')->title('Status'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(150)
                ->addClass('text-right'),
        ];
    }

    protected function filename(): string
    {
        return 'Vendors_' . date('YmdHis');
    }
}
