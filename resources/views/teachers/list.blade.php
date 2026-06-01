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

<div class="bg-white rounded-card shadow-card border border-gray-200 overflow-hidden p-6">
    <div class="overflow-x-auto dt-tailwind-container">
        {{ $dataTable->table(['class' => 'w-full text-sm data-table']) }}
    </div>
</div>

@push('modals')
<div x-data="{
    teacherId: null,
    teacherName: '',
    selectedClass: '',
    selectedSemester: '{{ $academic_setting?->active_semester_id ?? '' }}',
    courses: [],
    loadingCourses: false,
    init() {
        if (this.selectedSemester) {
            this.fetchCourses();
        }
    },
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
     @open-assign-modal.window="teacherId = $event.detail.id; teacherName = $event.detail.name; openModal('assign-teacher');"
     x-show="activeModal === 'assign-teacher'"
     class="fixed inset-0 overflow-y-auto"
style="display: none;">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div x-show="activeModal === 'assign-teacher'" x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75"
             @click="closeModal()"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div x-show="activeModal === 'assign-teacher'" x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
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
                        <label for="class_id" class="block text-xs font-medium text-gray-600 mb-1">Class</label>
                        <select id="class_id" name="class_id" x-model="selectedClass" @change="fetchCourses()" required
                                class="block w-full mt-1 rounded-lg border border-gray-300 px-3 py-2.5 text-sm text-gray-900 bg-white focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                            <option value="">Select Class</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->class_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="section_id" class="block text-xs font-medium text-gray-600 mb-1">Section</label>
                        <select id="section_id" name="section_id" required
                                class="block w-full mt-1 rounded-lg border border-gray-300 px-3 py-2.5 text-sm text-gray-900 bg-white focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                            <option value="">Select Section</option>
                            @foreach($sections as $section)
                                <option value="{{ $section->id }}" x-show="selectedClass == {{ $section->class_id }}">{{ $section->section_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="semester_id" class="block text-xs font-medium text-gray-600 mb-1">Semester</label>
                        <select id="semester_id" name="semester_id" x-model="selectedSemester" @change="fetchCourses()"
                                required
                                class="block w-full mt-1 rounded-lg border border-gray-300 px-3 py-2.5 text-sm text-gray-900 bg-white focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                            <option value="">Select Semester</option>
                            @foreach($semesters as $semester)
                                <option value="{{ $semester->id }}">{{ $semester->semester_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="course_id" class="block text-xs font-medium text-gray-600 mb-1">Course</label>
                        <select id="course_id" name="course_id" required
                                :disabled="loadingCourses || courses.length === 0"
                                class="block w-full mt-1 rounded-lg border border-gray-300 px-3 py-2.5 text-sm text-gray-900 bg-white focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 disabled:opacity-50 disabled:cursor-not-allowed">
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
                    <button type="button" @click="closeModal()"
                            class="mt-3 inline-flex justify-center w-full px-4 py-2 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:col-start-1 sm:text-sm">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endpush
@push('scripts')
    {{ $dataTable->scripts() }}
@endpush

@endsection
