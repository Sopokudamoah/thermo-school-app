@extends('layouts.app')

@section('page-title', 'Classes')

@section('content')

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="font-heading text-xl font-bold text-gray-900">Classes</h1>
            <nav class="flex items-center gap-1.5 mt-1 text-sm text-gray-500">
                <a href="{{ route('home') }}" class="hover:text-indigo-600 transition-colors">Home</a>
                <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                <span class="text-gray-900">Classes</span>
            </nav>
        </div>
        @can('create classes')
            <button @click="$dispatch('open-modal', 'add-class')"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 transition-colors shadow-sm">
                <i data-lucide="plus" class="w-4 h-4"></i> Add Class
            </button>
        @endcan
</div>

@isset($school_classes)
<div class="space-y-5">
    @foreach ($school_classes as $school_class)
        @php
            $current_sections_count = isset($school_sections) ? $school_sections->where('class_id', $school_class->id)->count() : 0;
            $total_sections = 0;
        @endphp

    <div class="bg-white rounded-card shadow-card border border-gray-200 overflow-hidden"
         x-data="{ activeTab: 'sections', openSection: null }">

        {{-- Tab header --}}
        <div class="flex items-center gap-0 border-b border-gray-200 px-4 pt-2">
            <button @click="activeTab = 'sections'"
                    :class="activeTab === 'sections' ? 'border-b-2 border-indigo-600 text-indigo-600' : 'text-gray-500 hover:text-gray-800'"
                    class="flex items-center gap-1.5 px-3 py-2.5 text-sm font-medium -mb-px transition-colors">
                <i data-lucide="git-branch" class="w-4 h-4"></i>
                {{ $school_class->class_name }}
                <span
                    class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-indigo-100 text-indigo-600 ml-1">
                    {{ $current_sections_count }}
                </span>
            </button>
            <button @click="activeTab = 'syllabus'"
                    :class="activeTab === 'syllabus' ? 'border-b-2 border-indigo-600 text-indigo-600' : 'text-gray-500 hover:text-gray-800'"
                    class="flex items-center gap-1.5 px-3 py-2.5 text-sm font-medium -mb-px transition-colors">
                <i data-lucide="book-marked" class="w-4 h-4"></i>
                Syllabus
                <span
                    class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 text-gray-600 ml-1"
                    :class="activeTab === 'syllabus' ? 'bg-indigo-100 text-indigo-600' : 'bg-gray-100 text-gray-600'">
                    {{ count($school_class->syllabi ?? []) }}
                </span>
            </button>
            <button @click="activeTab = 'courses'"
                    :class="activeTab === 'courses' ? 'border-b-2 border-indigo-600 text-indigo-600' : 'text-gray-500 hover:text-gray-800'"
                    class="flex items-center gap-1.5 px-3 py-2.5 text-sm font-medium -mb-px transition-colors">
                <i data-lucide="book-open" class="w-4 h-4"></i>
                Courses
                <span
                    class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 text-gray-600 ml-1"
                    :class="activeTab === 'courses' ? 'bg-indigo-100 text-indigo-600' : 'bg-gray-100 text-gray-600'">
                    {{ count($school_class->courses ?? []) }}
                </span>
            </button>
        </div>

        {{-- Tab body --}}
        <div class="p-4">

            {{-- Sections tab --}}
            <div x-show="activeTab === 'sections'">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="text-sm font-semibold text-gray-700">Sections</h4>
                    @can('create sections')
                        <button @click="$dispatch('open-modal', 'add-section-{{ $school_class->id }}')"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium text-white bg-indigo-600 hover:bg-indigo-700 transition-colors">
                            <i data-lucide="plus" class="w-3.5 h-3.5"></i> Add Section
                        </button>
                    @endcan
                </div>

            @isset($school_sections)
                <div class="space-y-2">
                    @foreach ($school_sections as $school_section)
                        @if ($school_section->class_id == $school_class->id)
                            @php $total_sections++; @endphp
                            <div class="border border-gray-200 rounded-lg overflow-hidden">
                                <button @click="openSection = openSection === {{ $school_section->id }} ? null : {{ $school_section->id }}"
                                        class="w-full flex items-center justify-between px-4 py-3 text-sm font-medium text-gray-800 hover:bg-gray-50 transition-colors text-left">
                                    <span>{{ $school_section->section_name }}</span>
                                    <i data-lucide="chevron-down" class="w-4 h-4 text-gray-400 shrink-0 transition-transform duration-200"
                                       :class="openSection === {{ $school_section->id }} ? 'rotate-180' : ''"></i>
                                </button>
                                <div x-show="openSection === {{ $school_section->id }}"
                                     x-transition:enter="transition ease-out duration-150"
                                     x-transition:enter-start="opacity-0"
                                     x-transition:enter-end="opacity-100"
                                     class="border-t border-gray-100 px-4 py-3 bg-gray-50">
                                    <div class="flex items-center justify-between mb-3">
                                        <span class="text-sm text-gray-600">Room No: <span class="font-medium text-gray-900">{{ $school_section->room_no }}</span></span>
                                        @can('edit sections')
                                        <a href="{{ route('section.edit', ['id' => $school_section->id]) }}"
                                           class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 transition-colors border border-gray-200">
                                            <i data-lucide="pencil" class="w-3.5 h-3.5"></i> Edit
                                        </a>
                                        @endcan
                                    </div>
                                    <div class="flex flex-wrap gap-2">
                                        <a href="{{ route('student.list.show', ['class_id' => $school_class->id, 'section_id' => $school_section->id, 'section_name' => $school_section->section_name]) }}"
                                           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium text-indigo-600 bg-indigo-50 hover:bg-indigo-100 transition-colors">
                                            <i data-lucide="users" class="w-3.5 h-3.5"></i>
                                            View Students
                                        </a>
                                        <a href="{{ route('section.routine.show', ['class_id' => $school_class->id, 'section_id' => $school_section->id]) }}"
                                           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 transition-colors">
                                            <i data-lucide="clock" class="w-3.5 h-3.5"></i>
                                            View Routine
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach

                    @if($total_sections === 0)
                    <div class="flex flex-col items-center py-8 text-center">
                        <i data-lucide="git-branch" class="w-7 h-7 text-gray-300 mb-2"></i>
                        <p class="text-sm text-gray-500">No sections for this class yet.</p>
                    </div>
                    @endif
                </div>
                @endisset
            </div>

            {{-- Syllabus tab --}}
            <div x-show="activeTab === 'syllabus'">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="text-sm font-semibold text-gray-700">Syllabus</h4>
                    @can('create syllabi')
                        <button @click="$dispatch('open-modal', 'add-syllabus-{{ $school_class->id }}')"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium text-white bg-indigo-600 hover:bg-indigo-700 transition-colors">
                            <i data-lucide="plus" class="w-3.5 h-3.5"></i> Add Syllabus
                        </button>
                    @endcan
                </div>

            @isset($school_class->syllabi)
                @if(count($school_class->syllabi) > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200">
                                <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase">Syllabus Name</th>
                                <th class="px-4 py-2.5 text-right text-xs font-semibold text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($school_class->syllabi as $syllabus)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-gray-900">{{ $syllabus->syllabus_name }}</td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ asset('storage/'.$syllabus->syllabus_file_path) }}"
                                       class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 transition-colors border border-gray-200">
                                        <i data-lucide="download" class="w-3.5 h-3.5"></i> Download
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="flex flex-col items-center py-8 text-center">
                    <i data-lucide="book-marked" class="w-7 h-7 text-gray-300 mb-2"></i>
                    <p class="text-sm text-gray-500">No syllabi uploaded for this class.</p>
                </div>
                @endif
                @endisset
            </div>

            {{-- Courses tab --}}
            <div x-show="activeTab === 'courses'">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="text-sm font-semibold text-gray-700">Courses</h4>
                    @can('create courses')
                        <button @click="$dispatch('open-modal', 'add-course-{{ $school_class->id }}')"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium text-white bg-indigo-600 hover:bg-indigo-700 transition-colors">
                            <i data-lucide="plus" class="w-3.5 h-3.5"></i> Add Course
                        </button>
                    @endcan
                </div>

            @isset($school_class->courses)
                @if(count($school_class->courses) > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200">
                                <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase">Course Name</th>
                                <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase">Type</th>
                                <th class="px-4 py-2.5 text-right text-xs font-semibold text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($school_class->courses as $course)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 font-medium text-gray-900">{{ $course->course_name }}</td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700">{{ $course->course_type }}</span>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    @can('edit courses')
                                    <a href="{{ route('course.edit', ['id' => $course->id]) }}"
                                       class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 transition-colors border border-gray-200">
                                        <i data-lucide="pencil" class="w-3.5 h-3.5"></i> Edit
                                    </a>
                                    @endcan
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="flex flex-col items-center py-8 text-center">
                    <i data-lucide="book-open" class="w-7 h-7 text-gray-300 mb-2"></i>
                    <p class="text-sm text-gray-500">No courses assigned to this class.</p>
                </div>
                @endif
                @endisset
            </div>

        </div>

        {{-- Card footer --}}
        <div class="px-4 py-3 border-t border-gray-100 bg-gray-50 flex items-center justify-between">
            @isset($total_sections)
            <span class="text-xs text-gray-500">
                <span class="font-medium text-gray-700">{{ $total_sections }}</span> section{{ $total_sections !== 1 ? 's' : '' }}
            </span>
            @endisset
            @can('edit classes')
            <a href="{{ route('class.edit', ['id' => $school_class->id]) }}"
               class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 transition-colors border border-gray-200">
                <i data-lucide="pencil" class="w-3.5 h-3.5"></i> Edit Class
            </a>
            @endcan
        </div>

        @push('modals')
            {{-- Modals for this class --}}
            @can('create sections')
                <div x-show="activeModal === 'add-section-{{ $school_class->id }}'"
                     class="fixed inset-0 overflow-y-auto" style="display: none;">
                    <div
                        class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                        <div x-show="activeModal === 'add-section-{{ $school_class->id }}'"
                             x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                             x-transition:enter-end="opacity-100"
                             x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
                             x-transition:leave-end="opacity-0"
                             class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75"
                             @click="closeModal()"></div>
                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen"
                              aria-hidden="true">&#8203;</span>
                        <div x-show="activeModal === 'add-section-{{ $school_class->id }}'"
                             x-transition:enter="ease-out duration-300"
                             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                             x-transition:leave="ease-in duration-200"
                             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                             class="inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                            <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-100">
                                <h3 class="text-lg font-semibold text-gray-900">Add Section
                                    to {{ $school_class->class_name }}</h3>
                                <button @click="closeModal()" class="text-gray-400 hover:text-gray-500">
                                    <i data-lucide="x" class="w-5 h-5"></i>
                                </button>
                            </div>
                            <form action="{{ route('school.section.create') }}" method="POST" class="space-y-4">
                                @csrf
                                <input type="hidden" name="class_id" value="{{ $school_class->id }}">
                                <input type="hidden" name="session_id" value="{{ $current_school_session_id }}">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Section Name <sup
                                            class="text-indigo-500">*</sup></label>
                                    <input type="text" name="section_name" required placeholder="e.g. Section A"
                                           class="block w-full rounded-lg border border-gray-300 px-3.5 py-2 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Room No <sup
                                            class="text-indigo-500">*</sup></label>
                                    <input type="text" name="room_no" required placeholder="e.g. Room 101"
                                           class="block w-full rounded-lg border border-gray-300 px-3.5 py-2 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                                </div>
                                <div class="mt-6 flex flex-col sm:flex-row-reverse gap-3">
                                    <button type="submit"
                                            class="inline-flex justify-center items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-sm px-4 py-2 rounded-lg transition-colors">
                                        <i data-lucide="check" class="w-4 h-4"></i> Create Section
                                    </button>
                                    <button type="button" @click="closeModal()"
                                            class="inline-flex justify-center items-center gap-2 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-semibold text-sm px-4 py-2 rounded-lg transition-colors">
                                        Cancel
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endcan

            @can('create courses')
                <div x-show="activeModal === 'add-course-{{ $school_class->id }}'"
                     class="fixed inset-0 overflow-y-auto" style="display: none;">
                    <div
                        class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                        <div x-show="activeModal === 'add-course-{{ $school_class->id }}'"
                             x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                             x-transition:enter-end="opacity-100"
                             x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
                             x-transition:leave-end="opacity-0"
                             class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75"
                             @click="closeModal()"></div>
                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen"
                              aria-hidden="true">&#8203;</span>
                        <div x-show="activeModal === 'add-course-{{ $school_class->id }}'"
                             x-transition:enter="ease-out duration-300"
                             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                             x-transition:leave="ease-in duration-200"
                             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                             class="inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                            <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-100">
                                <h3 class="text-lg font-semibold text-gray-900">Add Course
                                    to {{ $school_class->class_name }}</h3>
                                <button @click="closeModal()" class="text-gray-400 hover:text-gray-500">
                                    <i data-lucide="x" class="w-5 h-5"></i>
                                </button>
                            </div>
                            <form action="{{ route('school.course.create') }}" method="POST" class="space-y-4">
                                @csrf
                                <input type="hidden" name="class_id" value="{{ $school_class->id }}">
                                <input type="hidden" name="session_id" value="{{ $current_school_session_id }}">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Course Name <sup
                                            class="text-indigo-500">*</sup></label>
                                    <input type="text" name="course_name" required placeholder="e.g. Mathematics"
                                           class="block w-full rounded-lg border border-gray-300 px-3.5 py-2 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Course Type <sup
                                            class="text-indigo-500">*</sup></label>
                                    <select name="course_type" required
                                            class="block w-full rounded-lg border border-gray-300 px-3.5 py-2 text-sm text-gray-900 bg-white focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                                        <option value="Theory">Theory</option>
                                        <option value="Practical">Practical</option>
                                        <option value="Both">Both</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Semester <sup
                                            class="text-indigo-500">*</sup></label>
                                    <select name="semester_id" required
                                            class="block w-full rounded-lg border border-gray-300 px-3.5 py-2 text-sm text-gray-900 bg-white focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                                        <option value="">Select Semester</option>
                                        @isset($semesters)
                                            @foreach($semesters as $semester)
                                                <option
                                                    value="{{ $semester->id }}" {{ ($academic_setting?->active_semester_id == $semester->id) ? 'selected' : '' }}>
                                                    {{ $semester->semester_name }}
                                                </option>
                                            @endforeach
                                        @endisset
                                    </select>
                                </div>
                                <div class="mt-6 flex flex-col sm:flex-row-reverse gap-3">
                                    <button type="submit"
                                            class="inline-flex justify-center items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-sm px-4 py-2 rounded-lg transition-colors">
                                        <i data-lucide="check" class="w-4 h-4"></i> Create Course
                                    </button>
                                    <button type="button" @click="closeModal()"
                                            class="inline-flex justify-center items-center gap-2 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-semibold text-sm px-4 py-2 rounded-lg transition-colors">
                                        Cancel
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endcan

            @can('create syllabi')
                <div x-show="activeModal === 'add-syllabus-{{ $school_class->id }}'"
                     class="fixed inset-0 overflow-y-auto" style="display: none;">
                    <div
                        class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                        <div x-show="activeModal === 'add-syllabus-{{ $school_class->id }}'"
                             x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                             x-transition:enter-end="opacity-100"
                             x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
                             x-transition:leave-end="opacity-0"
                             class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75"
                             @click="closeModal()"></div>
                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen"
                              aria-hidden="true">&#8203;</span>
                        <div x-show="activeModal === 'add-syllabus-{{ $school_class->id }}'"
                             x-transition:enter="ease-out duration-300"
                             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                             x-transition:leave="ease-in duration-200"
                             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                             class="inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                            <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-100">
                                <h3 class="text-lg font-semibold text-gray-900">Add Syllabus
                                    to {{ $school_class->class_name }}</h3>
                                <button @click="closeModal()" class="text-gray-400 hover:text-gray-500">
                                    <i data-lucide="x" class="w-5 h-5"></i>
                                </button>
                            </div>
                            <form action="{{ route('syllabus.store') }}" method="POST" enctype="multipart/form-data"
                                  class="space-y-4">
                                @csrf
                                <input type="hidden" name="class_id" value="{{ $school_class->id }}">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Syllabus Name <sup
                                            class="text-indigo-500">*</sup></label>
                                    <input type="text" name="syllabus_name" required
                                           placeholder="e.g. Mathematics Syllabus 2026"
                                           class="block w-full rounded-lg border border-gray-300 px-3.5 py-2 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Course <span
                                            class="text-gray-400 text-xs font-normal">(Optional)</span></label>
                                    <select name="course_id"
                                            class="block w-full rounded-lg border border-gray-300 px-3.5 py-2 text-sm text-gray-900 bg-white focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                                        <option value="0">General Class Syllabus</option>
                                        @isset($school_class->courses)
                                            @foreach($school_class->courses as $course)
                                                <option value="{{ $course->id }}">{{ $course->course_name }}</option>
                                            @endforeach
                                        @endisset
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Syllabus File <sup
                                            class="text-indigo-500">*</sup></label>
                                    <input type="file" name="file" required
                                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer">
                                    <p class="mt-1 text-xs text-gray-500">Supported formats: PDF, DOC, DOCX, ZIP, JPG,
                                        PNG (Max 5MB)</p>
                                </div>
                                <div class="mt-6 flex flex-col sm:flex-row-reverse gap-3">
                                    <button type="submit"
                                            class="inline-flex justify-center items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-sm px-4 py-2 rounded-lg transition-colors">
                                        <i data-lucide="check" class="w-4 h-4"></i> Upload Syllabus
                                    </button>
                                    <button type="button" @click="closeModal()"
                                            class="inline-flex justify-center items-center gap-2 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-semibold text-sm px-4 py-2 rounded-lg transition-colors">
                                        Cancel
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endcan
        @endpush
    </div>

    @endforeach
</div>
@endisset

    @push('modals')
        @can('create classes')
            <div x-show="activeModal === 'add-class'"
                 class="fixed inset-0 overflow-y-auto" style="display: none;">
                <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                    <div x-show="activeModal === 'add-class'"
                         x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" @click="closeModal()"></div>
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                    <div x-show="activeModal === 'add-class'"
                         x-transition:enter="ease-out duration-300"
                         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                         x-transition:leave="ease-in duration-200"
                         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                         class="inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                        <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-100">
                            <h3 class="text-lg font-semibold text-gray-900">Add New Class</h3>
                            <button @click="closeModal()" class="text-gray-400 hover:text-gray-500">
                                <i data-lucide="x" class="w-5 h-5"></i>
                            </button>
                        </div>
                        <form action="{{ route('school.class.create') }}" method="POST" class="space-y-4">
                            @csrf
                            <input type="hidden" name="session_id" value="{{ $current_school_session_id }}">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Class Name <sup
                                        class="text-indigo-500">*</sup></label>
                                <input type="text" name="class_name" required
                                       placeholder="e.g. Class 1, Senior Secondary 1"
                                       class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                            </div>
                            <div class="mt-6 flex flex-col sm:flex-row-reverse gap-3">
                                <button type="submit"
                                        class="inline-flex justify-center items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-sm px-4 py-2.5 rounded-lg transition-colors">
                                    <i data-lucide="check" class="w-4 h-4"></i> Create Class
                                </button>
                                <button type="button" @click="closeModal()"
                                        class="inline-flex justify-center items-center gap-2 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-semibold text-sm px-4 py-2.5 rounded-lg transition-colors">
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endcan
    @endpush

@endsection
