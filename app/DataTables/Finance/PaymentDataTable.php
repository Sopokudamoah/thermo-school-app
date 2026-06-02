<?php

namespace App\DataTables\Finance;

use App\Models\Finance\Payment;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class PaymentDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('student_name', function ($payment) {
                return ($payment->student->first_name ?? '') . ' ' . ($payment->student->last_name ?? '');
            })
            ->editColumn('payment_date', function ($payment) {
                return $payment->payment_date ? $payment->payment_date->format('M d, Y') : 'N/A';
            })
            ->editColumn('amount', function ($payment) {
                return '<span class="text-emerald-600 font-bold">$' . number_format($payment->amount, 2) . '</span>';
            })
            ->editColumn('method', function ($payment) {
                return '<span class="capitalize">' . str_replace('_', ' ', $payment->method) . '</span>';
            })
            ->addColumn('action', 'finance.datatables.actions.payments')
            ->setRowId('id')
            ->rawColumns(['amount', 'method', 'action']);
    }

    public function query(Payment $model): QueryBuilder
    {
        return $model->newQuery()->with(['student']);
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('payments-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(2, 'desc')
            ->selectStyleSingle()
            ->parameters([
                'drawCallback' => 'function() { if(window.lucide) window.lucide.createIcons({ icons: window.lucide.icons }); }',
                'responsive' => true,
                'language' => [
                    'search' => '',
                    'searchPlaceholder' => 'Search payments…',
                ],
            ]);
    }

    public function getColumns(): array
    {
        return [
            Column::make('receipt_number')->title('Receipt #'),
            Column::computed('student_name')->title('Student'),
            Column::make('payment_date')->title('Date'),
            Column::make('amount')->title('Amount'),
            Column::make('method')->title('Method'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(150)
                ->addClass('text-right'),
        ];
    }

    protected function filename(): string
    {
        return 'Payments_' . date('YmdHis');
    }
}
