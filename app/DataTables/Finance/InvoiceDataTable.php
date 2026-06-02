<?php

namespace App\DataTables\Finance;

use App\Models\Finance\Invoice;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class InvoiceDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('student_name', function ($invoice) {
                return ($invoice->student->first_name ?? '') . ' ' . ($invoice->student->last_name ?? '');
            })
            ->editColumn('issue_date', function ($invoice) {
                return $invoice->issue_date ? $invoice->issue_date->format('M d, Y') : 'N/A';
            })
            ->editColumn('total', function ($invoice) {
                return '$' . number_format($invoice->total, 2);
            })
            ->editColumn('balance', function ($invoice) {
                return '<span class="text-red-600 font-semibold">$' . number_format($invoice->balance, 2) . '</span>';
            })
            ->editColumn('status', function ($invoice) {
                $statusColors = [
                    'draft' => 'bg-gray-100 text-gray-800',
                    'pending' => 'bg-amber-100 text-amber-800',
                    'partially_paid' => 'bg-blue-100 text-blue-800',
                    'paid' => 'bg-emerald-100 text-emerald-800',
                    'overdue' => 'bg-red-100 text-red-800',
                    'cancelled' => 'bg-gray-100 text-gray-500',
                ];
                $color = $statusColors[$invoice->status] ?? 'bg-gray-100 text-gray-800';
                $statusLabel = ucfirst(str_replace('_', ' ', $invoice->status));
                return '<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium ' . $color . '">' . $statusLabel . '</span>';
            })
            ->addColumn('action', 'finance.datatables.actions.invoices')
            ->setRowId('id')
            ->rawColumns(['balance', 'status', 'action']);
    }

    public function query(Invoice $model): QueryBuilder
    {
        $query = $model->newQuery()->with(['student']);

        if ($this->request()->has('status') && $this->request()->get('status')) {
            $query->where('status', $this->request()->get('status'));
        }

        if ($this->request()->has('invoice_number') && $this->request()->get('invoice_number')) {
            $query->where('invoice_number', 'like', '%' . $this->request()->get('invoice_number') . '%');
        }

        return $query;
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('invoices-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(0)
            ->selectStyleSingle()
            ->parameters([
                'drawCallback' => 'function() { if(window.lucide) window.lucide.createIcons({ icons: window.lucide.icons }); }',
                'responsive' => true,
                'language' => [
                    'search' => '',
                    'searchPlaceholder' => 'Search invoices…',
                ],
            ]);
    }

    public function getColumns(): array
    {
        return [
            Column::make('invoice_number')->title('Invoice #'),
            Column::computed('student_name')->title('Student'),
            Column::make('issue_date')->title('Date'),
            Column::make('total')->title('Total'),
            Column::make('balance')->title('Balance'),
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
        return 'Invoices_' . date('YmdHis');
    }
}
