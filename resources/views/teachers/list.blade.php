@extends('layouts.app')

@section('page-title', 'Teacher List')

@section('content')

<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="font-heading text-xl font-bold text-gray-900">Teacher List</h1>
        <nav class="flex items-center gap-1.5 mt-1 text-sm text-gray-500">
            <a href="{{ route('home') }}" class="hover:text-indigo-600 transition-colors">Home</a>
            <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
            <span class="text-gray-900">Teacher List</span>
        </nav>
    </div>
    @if (!session()->has('browse_session_id') && Auth::user()->role == "admin")
    <a href="{{ route('teacher.create.show') }}"
       class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm px-4 py-2.5 rounded-lg transition-colors">
        <i data-lucide="user-plus" class="w-4 h-4"></i>
        Add Teacher
    </a>
    @endif
</div>

<div class="bg-white rounded-card shadow-card border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm data-table">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200">
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Teacher</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Assigned Classes</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Phone</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($teachers as $teacher)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            @if (isset($teacher->photo))
                                <img src="{{ asset('/storage'.$teacher->photo) }}"
                                     class="w-8 h-8 rounded-full object-cover shrink-0" alt="Profile picture">
                            @else
                                <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center shrink-0">
                                    <i data-lucide="user" class="w-4 h-4 text-emerald-600"></i>
                                </div>
                            @endif
                            <span class="font-medium text-gray-900">{{ $teacher->first_name }} {{ $teacher->last_name }}</span>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-gray-600">{{ $teacher->email }}</td>
                    <td class="px-4 py-3 text-gray-600">
                        @php
                            $assigned = $teacher->assigned_classes->unique('class_id');
                        @endphp
                        @forelse ($assigned as $assign)
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-indigo-100 text-indigo-800 mr-1 mb-1">
                                {{ $assign->schoolClass->name }}
                            </span>
                        @empty
                            <span class="text-gray-400">No classes assigned</span>
                        @endforelse
                    </td>
                    <td class="px-4 py-3 text-gray-600">{{ $teacher->phone }}</td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-end gap-1">
                            <a href="{{ url('teachers/view/profile/'.$teacher->id) }}"
                               class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 transition-colors border border-gray-200">
                                <i data-lucide="eye" class="w-3.5 h-3.5"></i> Profile
                            </a>
                            @can('assign teachers')
                            <button type="button" @click="$dispatch('open-assign-modal', { id: {{ $teacher->id }}, name: '{{ $teacher->first_name }} {{ $teacher->last_name }}' })"
                               class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 transition-colors border border-gray-200">
                                <i data-lucide="user-plus" class="w-3.5 h-3.5"></i> Assign
                            </button>
                            @endcan
                            @can('edit users')
                            <a href="{{ route('teacher.edit.show', ['id' => $teacher->id]) }}"
                               class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 transition-colors border border-gray-200">
                                <i data-lucide="pencil" class="w-3.5 h-3.5"></i> Edit
                            </a>
                            @endcan
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-4 py-12 text-center">
                        <div class="flex flex-col items-center gap-2">
                            <i data-lucide="user-check" class="w-8 h-8 text-gray-300"></i>
                            <p class="text-sm text-gray-500">No teachers found.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div x-data="{
    isOpen: false,
    teacherId: null,
    teacherName: '',
    selectedClass: '',
    selectedSemester: '',
    courses: [],
    loadingCourses: false,
    fetchCourses() {
        if (!this.selectedClass || !this.selectedSemester) {
            this.courses = [];
            return;
        }
        this.loadingCourses = true;
        fetch(`/ajax/courses-by-class-semester?class_id=${this.selectedClass}&semester_id=${this.selectedSemester}`)
            .then(res => res.json())
            .then(data => {
                this.courses = data;
                this.loadingCourses = false;
                // Use setTimeout to ensure DOM is updated before Lucide runs
                setTimeout(() => { if(window.lucide) window.lucide.createIcons(window.lucide.icons); }, 10);
            })
            .catch(err => {
                console.error(err);
                this.loadingCourses = false;
            });
    }
}"
@open-assign-modal.window="isOpen = true; teacherId = $event.detail.id; teacherName = $event.detail.name; setTimeout(() => { if(window.lucide) window.lucide.createIcons(window.lucide.icons); }, 10);"
x-show="isOpen"
class="fixed inset-0 z-50 overflow-y-auto"
style="display: none;">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div x-show="isOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" @click="isOpen = false"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div x-show="isOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
            <div>
                <div class="flex items-center justify-center w-12 h-12 mx-auto bg-indigo-100 rounded-full">
                    <i data-lucide="user-plus" class="w-6 h-6 text-indigo-600"></i>
                </div>
                <div class="mt-3 text-center sm:mt-5">
                    <h3 class="text-lg font-medium leading-6 text-gray-900">Assign Teacher to Class</h3>
                    <div class="mt-2">
                        <p class="text-sm text-gray-500">Assigning <span class="font-bold text-gray-900" x-text="teacherName"></span> to a specific class, section, and course.</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('school.teacher.assign') }}" method="POST" class="mt-5 sm:mt-6">
                @csrf
                <input type="hidden" name="teacher_id" :value="teacherId">
                <input type="hidden" name="session_id" value="{{ $current_session_id }}">

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label for="class_id" class="block text-sm font-medium text-gray-700">Class</label>
                        <select id="class_id" name="class_id" x-model="selectedClass" @change="fetchCourses()" required class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">Select Class</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->class_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="section_id" class="block text-sm font-medium text-gray-700">Section</label>
                        <select id="section_id" name="section_id" required class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">Select Section</option>
                            @foreach($sections as $section)
                                <option value="{{ $section->id }}" x-show="selectedClass == {{ $section->class_id }}">{{ $section->section_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="semester_id" class="block text-sm font-medium text-gray-700">Semester</label>
                        <select id="semester_id" name="semester_id" x-model="selectedSemester" @change="fetchCourses()" required class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">Select Semester</option>
                            @foreach($semesters as $semester)
                                <option value="{{ $semester->id }}">{{ $semester->semester_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="course_id" class="block text-sm font-medium text-gray-700">Course</label>
                        <select id="course_id" name="course_id" required :disabled="loadingCourses || courses.length === 0" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">Select Course</option>
                            <template x-for="course in courses" :key="course.id">
                                <option :value="course.id" x-text="course.course_name"></option>
                            </template>
                        </select>
                        <p x-show="!loadingCourses && selectedClass && selectedSemester && courses.length === 0" class="mt-1 text-xs text-red-500">No courses found for this selection.</p>
                    </div>
                </div>

                <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                    <button type="submit" class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:col-start-2 sm:text-sm">
                        Assign Teacher
                    </button>
                    <button type="button" @click="isOpen = false" class="mt-3 inline-flex justify-center w-full px-4 py-2 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:col-start-1 sm:text-sm">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
