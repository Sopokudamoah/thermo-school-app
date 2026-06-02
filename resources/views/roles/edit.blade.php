@extends('layouts.app')

@section('page-title', 'Edit Role: ' . $role->name)

@section('content')

    @php
        $isSeeded = in_array($role->name, ['Administrator', 'Teacher', 'Finance']);

        $groupIcons = [
            'finance' => 'landmark',
            'users' => 'users',
            'notices' => 'bell',
            'events' => 'calendar',
            'syllabi' => 'book-open',
            'routines' => 'clock',
            'exams' => 'file-text',
            'grading' => 'award',
            'attendances' => 'check-square',
            'assignments' => 'clipboard-list',
            'marks' => 'bar-chart-2',
            'sessions' => 'layers',
            'semesters' => 'calendar-days',
            'courses' => 'graduation-cap',
            'teachers' => 'graduation-cap',
            'settings' => 'settings',
            'classes' => 'home',
            'sections' => 'layout-grid',
            'roles' => 'shield-check',
            'students' => 'user-plus',
            'general' => 'box'
        ];
    @endphp

    <div class="mb-6">
        <h1 class="font-heading text-xl font-bold text-gray-900">Edit Role: {{ $role->name }}</h1>
        <nav class="flex items-center gap-1.5 mt-1 text-sm text-gray-500">
            <a href="{{ route('home') }}" class="hover:text-indigo-600 transition-colors">Home</a>
            <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
            <a href="{{ route('roles.index') }}" class="hover:text-indigo-600 transition-colors">Roles</a>
            <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
            <span class="text-gray-900">Edit</span>
        </nav>
    </div>

    <form action="{{ route('roles.update', $role->id) }}" method="POST"
          x-data="{
        activeGroup: '{{ $permissions->keys()->first() }}',
        search: '',
        selectedPermissions: @js($rolePermissions),
        groups: @js($permissions->keys()->toArray()),
        allPermissions: @js($permissions->map(fn($group) => $group->pluck('name'))->toArray()),

        togglePermission(name) {
            if (this.selectedPermissions.includes(name)) {
                this.selectedPermissions = this.selectedPermissions.filter(p => p !== name);
            } else {
                this.selectedPermissions.push(name);
            }
        },

        isGroupSelected(group) {
            const groupPerms = this.allPermissions[group] || [];
            return groupPerms.length > 0 && groupPerms.every(p => this.selectedPermissions.includes(p));
        },

        toggleGroup(group) {
            const groupPerms = this.allPermissions[group] || [];
            if (this.isGroupSelected(group)) {
                this.selectedPermissions = this.selectedPermissions.filter(p => !groupPerms.includes(p));
            } else {
                groupPerms.forEach(p => {
                    if (!this.selectedPermissions.includes(p)) {
                        this.selectedPermissions.push(p);
                    }
                });
            }
        },

        selectAll() {
            let all = [];
            Object.values(this.allPermissions).forEach(perms => all.push(...perms));
            this.selectedPermissions = all;
        },

        deselectAll() {
            this.selectedPermissions = [];
        }
      }">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <!-- Sidebar: Role Info & Group Navigation -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Role Info Card -->
                <div class="bg-white rounded-card shadow-card border border-gray-200 p-5">
                    <h3 class="text-sm font-semibold text-gray-900 mb-4 uppercase tracking-wider">Role Details</h3>
                    <div class="space-y-4">
                        <div>
                            <label for="name" class="block text-xs font-medium text-gray-500 mb-1.5 uppercase">Name <sup
                                    class="text-red-500">*</sup></label>
                            <input type="text" name="name" id="name" value="{{ old('name', $role->name) }}" required
                                   {{ $isSeeded ? 'readonly' : '' }}
                                   class="block w-full rounded-lg border border-gray-300 px-3.5 py-2 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 {{ $isSeeded ? 'bg-gray-50' : '' }}"
                                   placeholder="e.g. Manager">
                            @if($isSeeded)
                                <p class="mt-1.5 text-[10px] text-amber-600 flex items-center gap-1 font-medium italic">
                                    <i data-lucide="alert-circle" class="w-3 h-3"></i>
                                    System roles cannot be renamed.
                                </p>
                            @endif
                            @error('name')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="pt-2">
                            <div class="text-xs font-medium text-gray-500 mb-2 uppercase">Summary</div>
                            <div
                                class="flex items-center justify-between p-3 bg-indigo-50 rounded-xl border border-indigo-100">
                                <span class="text-xs font-semibold text-indigo-700">Permissions Selected</span>
                                <span class="text-sm font-bold text-indigo-900"
                                      x-text="selectedPermissions.length">0</span>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-gray-100 flex flex-col gap-2">
                            <button type="submit"
                                    class="w-full inline-flex justify-center items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-sm px-4 py-2.5 rounded-lg transition-all shadow-sm">
                                <i data-lucide="save" class="w-4 h-4"></i> Update Role
                            </button>
                            <a href="{{ route('roles.index') }}"
                               class="w-full inline-flex justify-center items-center gap-2 bg-white hover:bg-gray-50 text-gray-700 font-medium text-sm px-4 py-2.5 rounded-lg border border-gray-300 transition-colors">
                                Cancel
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Group Navigation -->
                <div class="bg-white rounded-card shadow-card border border-gray-200 overflow-hidden">
                    <div class="p-4 bg-gray-50 border-b border-gray-200">
                        <h3 class="text-xs font-bold text-gray-900 uppercase tracking-wider">Permission Groups</h3>
                    </div>
                    <div class="max-h-[500px] overflow-y-auto custom-scrollbar">
                        <nav class="p-2 space-y-1">
                            @foreach($permissions as $group => $groupPerms)
                                <button type="button"
                                        @click="activeGroup = '{{ $group }}'"
                                        :class="activeGroup === '{{ $group }}' ? 'bg-indigo-50 text-indigo-700 ring-1 ring-inset ring-indigo-200' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'"
                                        class="w-full flex items-center justify-between px-3 py-2.5 text-sm font-medium rounded-lg transition-all group">
                                    <div class="flex items-center gap-3">
                                        <i data-lucide="{{ $groupIcons[$group] ?? 'box' }}"
                                           :class="activeGroup === '{{ $group }}' ? 'text-indigo-600' : 'text-gray-400 group-hover:text-gray-600'"
                                           class="w-4 h-4"></i>
                                        <span>{{ ucfirst($group) }}</span>
                                    </div>
                                    <span
                                        :class="activeGroup === '{{ $group }}' ? 'bg-indigo-200 text-indigo-800' : 'bg-gray-100 text-gray-600'"
                                        class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold">
                                    {{ count($groupPerms) }}
                                </span>
                                </button>
                            @endforeach
                        </nav>
                    </div>
                </div>
            </div>

            <!-- Main Content: Permission List -->
            <div class="lg:col-span-3">
                <div
                    class="bg-white rounded-card shadow-card border border-gray-200 flex flex-col h-full min-h-[600px]">
                    <!-- Toolbar -->
                    <div
                        class="p-5 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white sticky top-0 z-10 rounded-t-card">
                        <div class="relative flex-1 max-w-md">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <i data-lucide="search" class="h-4 w-4 text-gray-400"></i>
                            </div>
                            <input type="text" x-model="search"
                                   class="block w-full pl-10 pr-4 py-2 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-100 focus:border-indigo-500 transition-all"
                                   placeholder="Search permissions...">
                        </div>

                        <div class="flex items-center gap-3">
                            <button type="button" @click="selectAll()"
                                    class="text-xs font-semibold text-indigo-600 hover:text-indigo-700 hover:bg-indigo-50 px-3 py-1.5 rounded-lg transition-colors">
                                Select All
                            </button>
                            <div class="w-px h-4 bg-gray-200"></div>
                            <button type="button" @click="deselectAll()"
                                    class="text-xs font-semibold text-red-600 hover:text-red-700 hover:bg-red-50 px-3 py-1.5 rounded-lg transition-colors">
                                Deselect All
                            </button>
                        </div>
                    </div>

                    <!-- Group Content -->
                    <div class="p-6 flex-1 overflow-y-auto custom-scrollbar">
                        @foreach($permissions as $group => $groupPermissions)
                            <div x-show="activeGroup === '{{ $group }}' || search !== ''"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 translate-y-2"
                                 x-transition:enter-end="opacity-100 translate-y-0"
                                 class="mb-8 last:mb-0">

                                <div class="flex items-center justify-between mb-5">
                                    <h4 class="text-sm font-bold text-gray-900 uppercase tracking-wider flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center">
                                            <i data-lucide="{{ $groupIcons[$group] ?? 'box' }}"
                                               class="w-4 h-4 text-indigo-600"></i>
                                        </div>
                                        {{ ucfirst($group) }}
                                    </h4>
                                    <label class="inline-flex items-center gap-2 cursor-pointer group">
                                        <input type="checkbox" :checked="isGroupSelected('{{ $group }}')"
                                               @change="toggleGroup('{{ $group }}')"
                                               class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                        <span
                                            class="text-xs font-medium text-gray-500 group-hover:text-gray-900 transition-colors">Select Group</span>
                                    </label>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-3">
                                    @foreach($groupPermissions as $permission)
                                        @php
                                            $label = $permission->name;
                                            if (strpos($label, '.') !== false) {
                                                $parts = explode('.', $label);
                                                $label = end($parts) . ' ' . (count($parts) > 2 ? $parts[1] : $parts[0]);
                                            }
                                            $label = ucwords(str_replace(['-', '_'], ' ', $label));
                                        @endphp
                                        <div
                                            x-show="search === '' || '{{ strtolower($permission->name) }}'.includes(search.toLowerCase()) || '{{ strtolower($label) }}'.includes(search.toLowerCase())"
                                            class="relative">
                                            <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                                   id="perm-{{ $permission->id }}"
                                                   x-model="selectedPermissions"
                                                   class="hidden peer">
                                            <label for="perm-{{ $permission->id }}"
                                                   class="flex items-start gap-3 p-3.5 rounded-xl border border-gray-100 bg-white hover:bg-gray-50 peer-checked:bg-indigo-50 peer-checked:border-indigo-200 peer-checked:ring-1 peer-checked:ring-indigo-200 transition-all cursor-pointer group h-full">
                                                <div class="flex-shrink-0 mt-0.5">
                                                    <div
                                                        class="w-4 h-4 rounded border border-gray-300 bg-white flex items-center justify-center peer-checked:border-indigo-600 peer-checked:bg-indigo-600 transition-all group-hover:border-indigo-400">
                                                        <template
                                                            x-if="selectedPermissions.includes('{{ $permission->name }}')">
                                                            <i data-lucide="check" class="w-3 h-3 text-white"></i>
                                                        </template>
                                                    </div>
                                                </div>
                                                <div class="flex-1">
                                                    <div
                                                        class="text-sm font-semibold text-gray-700 group-hover:text-gray-900 transition-colors">{{ $label }}</div>
                                                    <div
                                                        class="text-[10px] text-gray-400 font-mono mt-0.5">{{ $permission->name }}</div>
                                                </div>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach

                        <!-- Empty State for Search -->
                        <div
                            x-show="search !== '' && $el.parentElement.querySelectorAll('.relative[x-show*=\'search\']:not([style*=\'display: none\'])').length === 0"
                            class="flex flex-col items-center justify-center py-12 text-center">
                            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                <i data-lucide="search-x" class="w-8 h-8 text-gray-300"></i>
                            </div>
                            <h3 class="text-sm font-medium text-gray-900">No permissions found</h3>
                            <p class="text-xs text-gray-500 mt-1">Try adjusting your search term.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

@endsection
