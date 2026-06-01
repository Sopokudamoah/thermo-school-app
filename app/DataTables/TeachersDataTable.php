<?php

namespace App\DataTables;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class TeachersDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $academic_setting = \App\Models\AcademicSetting::first();
        $semester_id = $academic_setting->active_semester_id ?? null;

        return (new EloquentDataTable($query))
            ->addColumn('teacher', function ($teacher) {
                $photo = $teacher->photo
                    ? '<img src="' . asset(
                        '/storage' . $teacher->photo
                    ) . '" class="w-8 h-8 rounded-full object-cover shrink-0" alt="Profile picture">'
                    : '<div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center shrink-0"><i data-lucide="user" class="w-4 h-4 text-emerald-600"></i></div>';

                return '<div class="flex items-center gap-3">' . $photo . '<span class="font-medium text-gray-900">' . $teacher->first_name . ' ' . $teacher->last_name . '</span></div>';
            })
            ->addColumn('assigned_classes', function ($teacher) use ($semester_id) {
                $assigned = $teacher->assigned_classes;

                if ($semester_id) {
                    $assigned = $assigned->where('semester_id', $semester_id);
                }

                $assigned = $assigned->unique('class_id');

                if ($assigned->isEmpty()) {
                    return '<span class="text-gray-400">No classes assigned</span>';
                }

                $html = '';
                foreach ($assigned as $assign) {
                    $className = $assign->schoolClass->class_name ?? '—';
                    $sessionName = $assign->schoolSession->session_name ?? '';
                    $displayText = $sessionName ? "$className ($sessionName)" : $className;
                    $html .= '<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-indigo-100 text-indigo-800 mr-1 mb-1">' . $displayText . '</span>';
                }
                return $html;
            })
            ->addColumn('action', 'teachers.datatables.actions')
            ->setRowId('id')
            ->rawColumns(['teacher', 'assigned_classes', 'action']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(User $model): QueryBuilder
    {
        $session_id = $this->request()->get('session_id') ?: session('browse_session_id');
        $academic_setting = \App\Models\AcademicSetting::first();
        $semester_id = $academic_setting->active_semester_id ?? null;

        return $model->newQuery()
            ->where('role', 'teacher')
            ->with([
                'assigned_classes' => function ($query) use ($session_id, $semester_id) {
                    if ($session_id) {
                        $query->where('session_id', $session_id);
                    }
                    if ($semester_id) {
                        $query->where('semester_id', $semester_id);
                    }
                    $query->with(['schoolClass', 'schoolSession']);
                }
            ]);
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('teachers-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            //->dom('Bfrtip')
            ->orderBy(0)
            ->selectStyleSingle()
            ->parameters([
                'drawCallback' => 'function() { if(window.lucide) window.lucide.createIcons({ icons: window.lucide.icons }); }',
                'responsive' => true,
                'language' => [
                    'search' => '',
                    'searchPlaceholder' => 'Search teachers…',
                    'lengthMenu' => 'Show _MENU_ entries',
                    'info' => 'Showing _START_ to _END_ of _TOTAL_ teachers',
                    'infoEmpty' => 'No teachers found',
                    'zeroRecords' => 'No matching teachers found',
                ],
            ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::computed('teacher')->title('Teacher'),
            Column::make('email')->title('Email'),
            Column::computed('assigned_classes')->title('Assigned Classes'),
            Column::make('phone')->title('Phone'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(200)
                ->addClass('text-right'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Teachers_' . date('YmdHis');
    }
}
