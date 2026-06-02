<?php

namespace App\DataTables\Finance;

use App\Models\Finance\Discount;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class DiscountDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('type', function ($discount) {
                return ucfirst($discount->type);
            })
            ->editColumn('value', function ($discount) {
                if ($discount->type === 'percentage') {
                    return $discount->value . '%';
                }
                return \App\Helpers\MoneyHelper::format($discount->value);
            })
            ->editColumn('active', function ($discount) {
                $status = $discount->active ? 'Active' : 'Inactive';
                $color = $discount->active ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800';
                return '<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium ' . $color . '">' . $status . '</span>';
            })
            ->addColumn('action', 'finance.datatables.actions.discounts')
            ->setRowId('id')
            ->rawColumns(['active', 'action']);
    }

    public function query(Discount $model): QueryBuilder
    {
        return $model->newQuery();
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('discounts-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(0)
            ->selectStyleSingle()
            ->parameters([
                'drawCallback' => 'function() { if(window.lucide) window.lucide.createIcons({ icons: window.lucide.icons }); }',
                'responsive' => true,
                'language' => [
                    'search' => '',
                    'searchPlaceholder' => 'Search discounts…',
                ],
            ]);
    }

    public function getColumns(): array
    {
        return [
            Column::make('name')->title('Name'),
            Column::make('type')->title('Type'),
            Column::make('value')->title('Value'),
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
        return 'Discounts_' . date('YmdHis');
    }
}
