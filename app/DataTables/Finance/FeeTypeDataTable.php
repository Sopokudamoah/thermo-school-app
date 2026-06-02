<?php

namespace App\DataTables\Finance;

use App\Models\Finance\FeeType;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class FeeTypeDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('active', function ($feeType) {
                $status = $feeType->active ? 'Active' : 'Inactive';
                $color = $feeType->active ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800';
                return '<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium ' . $color . '">' . $status . '</span>';
            })
            ->editColumn('recurring', function ($feeType) {
                $status = $feeType->recurring ? 'Yes' : 'No';
                $color = $feeType->recurring ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800';
                return '<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium ' . $color . '">' . $status . '</span>';
            })
            ->editColumn('description', function ($feeType) {
                return \Illuminate\Support\Str::limit($feeType->description, 50);
            })
            ->addColumn('action', 'finance.datatables.actions.fee_types')
            ->setRowId('id')
            ->rawColumns(['active', 'recurring', 'action']);
    }

    public function query(FeeType $model): QueryBuilder
    {
        return $model->newQuery();
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('fee-types-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(1)
            ->selectStyleSingle()
            ->parameters([
                'drawCallback' => 'function() { if(window.lucide) window.lucide.createIcons({ icons: window.lucide.icons }); }',
                'responsive' => true,
                'language' => [
                    'search' => '',
                    'searchPlaceholder' => 'Search fee types…',
                ],
            ]);
    }

    public function getColumns(): array
    {
        return [
            Column::make('name')->title('Name'),
            Column::make('code')->title('Code'),
            Column::make('description')->title('Description'),
            Column::make('recurring')->title('Recurring'),
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
        return 'FeeTypes_' . date('YmdHis');
    }
}
