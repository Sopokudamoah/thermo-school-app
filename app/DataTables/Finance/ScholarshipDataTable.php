<?php

namespace App\DataTables\Finance;

use App\Models\Finance\Scholarship;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ScholarshipDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('type', function ($scholarship) {
                return ucfirst($scholarship->type);
            })
            ->editColumn('value', function ($scholarship) {
                if ($scholarship->type === 'percentage') {
                    return $scholarship->value . '%';
                }
                return '$' . number_format($scholarship->value, 2);
            })
            ->addColumn('students_count', function ($scholarship) {
                return $scholarship->students_count;
            })
            ->editColumn('active', function ($scholarship) {
                $status = $scholarship->active ? 'Active' : 'Inactive';
                $color = $scholarship->active ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800';
                return '<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium ' . $color . '">' . $status . '</span>';
            })
            ->addColumn('action', 'finance.datatables.actions.scholarships')
            ->setRowId('id')
            ->rawColumns(['active', 'action']);
    }

    public function query(Scholarship $model): QueryBuilder
    {
        return $model->newQuery()->withCount('students');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('scholarships-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(0)
            ->selectStyleSingle()
            ->parameters([
                'drawCallback' => 'function() { if(window.lucide) window.lucide.createIcons({ icons: window.lucide.icons }); }',
                'responsive' => true,
                'language' => [
                    'search' => '',
                    'searchPlaceholder' => 'Search scholarships…',
                ],
            ]);
    }

    public function getColumns(): array
    {
        return [
            Column::make('name')->title('Name'),
            Column::make('type')->title('Type'),
            Column::make('value')->title('Value'),
            Column::make('students_count')->title('Students')->searchable(false),
            Column::make('active')->title('Status'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(200)
                ->addClass('text-right'),
        ];
    }

    protected function filename(): string
    {
        return 'Scholarships_' . date('YmdHis');
    }
}
