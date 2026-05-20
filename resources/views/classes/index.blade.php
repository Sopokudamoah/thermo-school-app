@extends('layouts.app')

@section('page-title', 'Classes')

@section('content')

<div class="mb-6">
    <h1 class="font-heading text-xl font-bold text-gray-900">Classes</h1>
    <nav class="flex items-center gap-1.5 mt-1 text-sm text-gray-500">
        <a href="{{ route('home') }}" class="hover:text-indigo-600 transition-colors">Home</a>
        <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
        <span class="text-gray-900">Classes</span>
    </nav>
</div>

@isset($school_classes)
<div class="space-y-5">
    @foreach ($school_classes as $school_class)
    @php $total_sections = 0; @endphp

    <div class="bg-white rounded-card shadow-card border border-gray-200 overflow-hidden"
         x-data="{ activeTab: 'sections', openSection: null }">

        {{-- Tab header --}}
        <div class="flex items-center gap-0 border-b border-gray-200 px-4 pt-2">
            <button @click="activeTab = 'sections'"
                    :class="activeTab === 'sections' ? 'border-b-2 border-indigo-600 text-indigo-600' : 'text-gray-500 hover:text-gray-800'"
                    class="flex items-center gap-1.5 px-3 py-2.5 text-sm font-medium -mb-px transition-colors">
                <i data-lucide="git-branch" class="w-4 h-4"></i>
                {{ $school_class->class_name }}
            </button>
            <button @click="activeTab = 'syllabus'"
                    :class="activeTab === 'syllabus' ? 'border-b-2 border-indigo-600 text-indigo-600' : 'text-gray-500 hover:text-gray-800'"
                    class="flex items-center gap-1.5 px-3 py-2.5 text-sm font-medium -mb-px transition-colors">
                <i data-lucide="book-marked" class="w-4 h-4"></i>
                Syllabus
            </button>
            <button @click="activeTab = 'courses'"
                    :class="activeTab === 'courses' ? 'border-b-2 border-indigo-600 text-indigo-600' : 'text-gray-500 hover:text-gray-800'"
                    class="flex items-center gap-1.5 px-3 py-2.5 text-sm font-medium -mb-px transition-colors">
                <i data-lucide="book-open" class="w-4 h-4"></i>
                Courses
            </button>
        </div>

        {{-- Tab body --}}
        <div class="p-4">

            {{-- Sections tab --}}
            <div x-show="activeTab === 'sections'">
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
    </div>

    @endforeach
</div>
@endisset

@endsection
