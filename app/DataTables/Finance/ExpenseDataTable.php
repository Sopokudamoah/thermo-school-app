<?php

namespace App\DataTables\Finance;

use App\Models\Finance\Expense;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ExpenseDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('category_name', function ($expense) {
                return $expense->category->name ?? 'N/A';
            })
            ->addColumn('vendor_name', function ($expense) {
                return $expense->vendor->name ?? 'N/A';
            })
            ->editColumn('expense_date', function ($expense) {
                return $expense->expense_date ? $expense->expense_date->format('M d, Y') : 'N/A';
            })
            ->editColumn('amount', function ($expense) {
                return \App\Helpers\MoneyHelper::format($expense->amount);
            })
            ->editColumn('status', function ($expense) {
                $statusColors = [
                    'pending' => 'bg-amber-100 text-amber-800',
                    'approved' => 'bg-emerald-100 text-emerald-800',
                    'rejected' => 'bg-red-100 text-red-800',
                ];
                $color = $statusColors[$expense->status] ?? 'bg-gray-100 text-gray-800';
                return '<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium ' . $color . '">' . ucfirst(
                        $expense->status
                    ) . '</span>';
            })
            ->addColumn('action', 'finance.datatables.actions.expenses')
            ->setRowId('id')
            ->rawColumns(['status', 'action']);
    }

    public function query(Expense $model): QueryBuilder
    {
        return $model->newQuery()->with(['category', 'vendor']);
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('expenses-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(2, 'desc')
            ->selectStyleSingle()
            ->parameters([
                'drawCallback' => 'function() { if(window.lucide) window.lucide.createIcons({ icons: window.lucide.icons }); }',
                'responsive' => true,
                'language' => [
                    'search' => '',
                    'searchPlaceholder' => 'Search expenses…',
                ],
            ]);
    }

    public function getColumns(): array
    {
        return [
            Column::make('title')->title('Title'),
            Column::computed('category_name')->title('Category'),
            Column::make('expense_date')->title('Date'),
            Column::make('amount')->title('Amount'),
            Column::computed('vendor_name')->title('Vendor'),
            Column::make('status')->title('Status'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(150)
                ->addClass('text-right'),
        ];
    }

    protected function filename(): string
    {
        return 'Expenses_' . date('YmdHis');
    }
}
