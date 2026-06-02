<?php

namespace App\DataTables\Finance;

use App\Models\Finance\FeeStructure;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class FeeStructureDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('session', function ($feeStructure) {
                return $feeStructure->session->session_name ?? 'N/A';
            })
            ->addColumn('semester', function ($feeStructure) {
                return $feeStructure->semester->semester_name ?? 'N/A';
            })
            ->addColumn('class', function ($feeStructure) {
                return $feeStructure->school_class->class_name ?? 'All Classes';
            })
            ->addColumn('total_amount', function ($feeStructure) {
                return '$' . number_format($feeStructure->items->sum('amount'), 2);
            })
            ->addColumn('action', 'finance.datatables.actions.fee_structures')
            ->setRowId('id')
            ->rawColumns(['action']);
    }

    public function query(FeeStructure $model): QueryBuilder
    {
        return $model->newQuery()->with(['session', 'semester', 'school_class', 'items']);
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('fee-structures-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(0)
            ->selectStyleSingle()
            ->parameters([
                'drawCallback' => 'function() { if(window.lucide) window.lucide.createIcons({ icons: window.lucide.icons }); }',
                'responsive' => true,
                'language' => [
                    'search' => '',
                    'searchPlaceholder' => 'Search fee structures…',
                ],
            ]);
    }

    public function getColumns(): array
    {
        return [
            Column::make('name')->title('Name'),
            Column::computed('session')->title('Session'),
            Column::computed('semester')->title('Semester'),
            Column::computed('class')->title('Class'),
            Column::computed('total_amount')->title('Total'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(150)
                ->addClass('text-right'),
        ];
    }

    protected function filename(): string
    {
        return 'FeeStructures_' . date('YmdHis');
    }
}
