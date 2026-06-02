<?php

namespace App\DataTables\Finance;

use App\Models\Finance\Budget;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class BudgetDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('total_allocated', function ($budget) {
                return \App\Helpers\MoneyHelper::format($budget->total_allocated);
            })
            ->editColumn('active', function ($budget) {
                $status = $budget->active ? 'Active' : 'Inactive';
                $color = $budget->active ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800';
                return '<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium ' . $color . '">' . $status . '</span>';
            })
            ->addColumn('action', 'finance.datatables.actions.budgets')
            ->setRowId('id')
            ->rawColumns(['active', 'action']);
    }

    public function query(Budget $model): QueryBuilder
    {
        return $model->newQuery();
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('budgets-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(1, 'desc')
            ->selectStyleSingle()
            ->parameters([
                'drawCallback' => 'function() { if(window.lucide) window.lucide.createIcons({ icons: window.lucide.icons }); }',
                'responsive' => true,
                'language' => [
                    'search' => '',
                    'searchPlaceholder' => 'Search budgets…',
                ],
            ]);
    }

    public function getColumns(): array
    {
        return [
            Column::make('name')->title('Name'),
            Column::make('year')->title('Year'),
            Column::make('total_allocated')->title('Total Allocated'),
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
        return 'Budgets_' . date('YmdHis');
    }
}
