<?php

namespace App\DataTables;

use App\Models\Promotion;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class StudentsDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('student', function ($promotion) {
                $student = $promotion->student;
                $photo = ($student && $student->photo)
                    ? '<img src="' . asset(
                        '/storage' . $student->photo
                    ) . '" class="w-8 h-8 rounded-full object-cover shrink-0" alt="Profile picture">'
                    : '<div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center shrink-0"><i data-lucide="user" class="w-4 h-4 text-indigo-500"></i></div>';

                $name = $student ? ($student->first_name . ' ' . $student->last_name) : 'N/A';
                return '<div class="flex items-center gap-3">' . $photo . '<span class="font-medium text-gray-900">' . $name . '</span></div>';
            })
            ->addColumn('email', function ($promotion) {
                return $promotion->student->email ?? 'N/A';
            })
            ->addColumn('phone', function ($promotion) {
                return $promotion->student->phone ?? 'N/A';
            })
            ->addColumn('action', 'students.datatables.actions')
            ->setRowId('id')
            ->rawColumns(['student', 'action']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Promotion $model): QueryBuilder
    {
        $session_id = $this->request()->get('session_id') ?: session('browse_session_id');
        $class_id = $this->request()->get('class_id');
        $section_id = $this->request()->get('section_id');

        $query = $model->newQuery()->with(['student', 'section', 'schoolClass']);

        if ($session_id) {
            $query->where('session_id', $session_id);
        }

        if ($class_id && $class_id != 0) {
            $query->where('class_id', $class_id);
        }

        if ($section_id && $section_id != 0) {
            $query->where('section_id', $section_id);
        }

        return $query;
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('students-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(1)
            ->selectStyleSingle()
            ->parameters([
                'drawCallback' => 'function() { if(window.lucide) window.lucide.createIcons({ icons: window.lucide.icons }); }',
                'responsive' => true,
                'language' => [
                    'search' => '',
                    'searchPlaceholder' => 'Search students…',
                    'lengthMenu' => 'Show _MENU_ entries',
                    'info' => 'Showing _START_ to _END_ of _TOTAL_ students',
                    'infoEmpty' => 'No students found',
                    'zeroRecords' => 'No matching students found',
                ],
            ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('id_card_number')->title('ID Card'),
            Column::computed('student')->title('Student'),
            Column::computed('email')->title('Email'),
            Column::computed('phone')->title('Phone'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(250)
                ->addClass('text-right'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Students_' . date('YmdHis');
    }
}
