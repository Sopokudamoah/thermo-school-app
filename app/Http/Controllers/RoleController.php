<?php

namespace App\Http\Controllers;

use App\DataTables\RoleDataTable;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'can:manage roles']);
    }

    public function index(RoleDataTable $dataTable)
    {
        return $dataTable->render('roles.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permissions' => 'nullable|array'
        ]);

        $role = Role::create(['name' => $request->name]);
        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        return redirect()->route('roles.index')->with('success', 'Role created successfully');
    }

    public function create()
    {
        $permissions = $this->getGroupedPermissions();
        return view('roles.create', compact('permissions'));
    }

    private function getGroupedPermissions()
    {
        return Permission::all()->groupBy(function ($perm) {
            if (strpos($perm->name, '.') !== false) {
                return explode('.', $perm->name)[0];
            }

            // Legacy grouping
            if (strpos($perm->name, 'users') !== false) {
                return 'users';
            }
            if (strpos($perm->name, 'notices') !== false) {
                return 'notices';
            }
            if (strpos($perm->name, 'events') !== false) {
                return 'events';
            }
            if (strpos($perm->name, 'syllabi') !== false) {
                return 'syllabi';
            }
            if (strpos($perm->name, 'routines') !== false) {
                return 'routines';
            }
            if (strpos($perm->name, 'exams') !== false) {
                return 'exams';
            }
            if (strpos($perm->name, 'grading') !== false) {
                return 'grading';
            }
            if (strpos($perm->name, 'attendances') !== false) {
                return 'attendances';
            }
            if (strpos($perm->name, 'assignments') !== false) {
                return 'assignments';
            }
            if (strpos($perm->name, 'marks') !== false) {
                return 'marks';
            }
            if (strpos($perm->name, 'sessions') !== false) {
                return 'sessions';
            }
            if (strpos($perm->name, 'semesters') !== false) {
                return 'semesters';
            }
            if (strpos($perm->name, 'courses') !== false) {
                return 'courses';
            }
            if (strpos($perm->name, 'teachers') !== false) {
                return 'teachers';
            }
            if (strpos($perm->name, 'academic settings') !== false) {
                return 'settings';
            }
            if (strpos($perm->name, 'classes') !== false) {
                return 'classes';
            }
            if (strpos($perm->name, 'sections') !== false) {
                return 'sections';
            }
            if (strpos($perm->name, 'roles') !== false) {
                return 'roles';
            }
            if (strpos($perm->name, 'students') !== false) {
                return 'students';
            }

            return 'general';
        });
    }

    public function edit(Role $role)
    {
        $permissions = $this->getGroupedPermissions();
        $rolePermissions = $role->permissions->pluck('name')->toArray();
        return view('roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    public function update(Request $request, Role $role)
    {
        $isSeeded = in_array($role->name, ['Administrator', 'Teacher', 'Finance']);

        $rules = [
            'permissions' => 'nullable|array'
        ];

        if (!$isSeeded) {
            $rules['name'] = 'required|unique:roles,name,' . $role->id;
        }

        $request->validate($rules);

        if (!$isSeeded) {
            $role->update(['name' => $request->name]);
        }

        $role->syncPermissions($request->permissions ?? []);

        return redirect()->route('roles.index')->with('success', 'Role updated successfully');
    }

    public function destroy(Role $role)
    {
        if (in_array($role->name, ['Administrator', 'Teacher', 'Finance'])) {
            return redirect()->back()->with('error', 'Seeded roles cannot be deleted');
        }
        $role->delete();
        return redirect()->route('roles.index')->with('success', 'Role deleted successfully');
    }
}
