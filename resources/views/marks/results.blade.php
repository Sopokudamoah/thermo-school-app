@extends('layouts.app')
@section('page-title', 'View Results')

@section('content')
<div class="mb-6">
    <h1 class="font-heading text-xl font-bold text-gray-900"><i data-lucide="table" class="inline w-5 h-5 mr-2"></i> View Results</h1>
    <nav class="flex items-center gap-1.5 mt-1 text-sm text-gray-500">
        <a href="{{route('home')}}" class="hover:text-indigo-600">Home</a>
        <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
        <span>View Results</span>
    </nav>
</div>

@if($selected_semester || $selected_class || $selected_section || $selected_course)
    <div class="mb-4 bg-white rounded-card shadow-card border border-gray-200 p-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <span class="block text-xs font-semibold text-gray-500 uppercase tracking-wider">Semester</span>
                <span class="text-sm font-medium text-gray-900">{{$selected_semester->semester_name ?? 'N/A'}}</span>
            </div>
            <div>
                <span class="block text-xs font-semibold text-gray-500 uppercase tracking-wider">Class</span>
                <span class="text-sm font-medium text-gray-900">{{$selected_class->class_name ?? 'N/A'}}</span>
            </div>
            <div>
                <span class="block text-xs font-semibold text-gray-500 uppercase tracking-wider">Section</span>
                <span class="text-sm font-medium text-gray-900">{{$selected_section->section_name ?? 'N/A'}}</span>
            </div>
            <div>
                <span class="block text-xs font-semibold text-gray-500 uppercase tracking-wider">Course</span>
                <span class="text-sm font-medium text-gray-900">{{$selected_course->course_name ?? 'N/A'}}</span>
            </div>
        </div>
    </div>
@endif

<div class="bg-white rounded-card shadow-card border border-gray-200 p-4 mb-5">
    <p class="text-sm font-medium text-gray-600 mb-3">Filter list by:</p>
    <form action="{{route('course.mark.list.show')}}" method="GET">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <div>
                <select class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 bg-white" name="semester_id" required>
                    <option value=""
                            disabled {{ (!isset($semester_id) || $semester_id == 0) && !isset($academic_setting->active_semester_id) ? 'selected' : '' }}>
                        Select Semester
                    </option>
                    @isset($semesters)
                        @foreach ($semesters as $semester)
                            <option
                                value="{{$semester->id}}" {{ (isset($semester_id) && $semester_id == $semester->id) || (!isset($semester_id) || $semester_id == 0) && isset($academic_setting->active_semester_id) && $academic_setting->active_semester_id == $semester->id ? 'selected' : '' }}>{{$semester->semester_name}}</option>
                        @endforeach
                    @endisset
                </select>
            </div>
            <div>
                <select onchange="getSectionsAndCourses(this);"
                        class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 bg-white"
                        name="class_id" id="class_id" aria-label="Class" required>
                    <option value="" disabled {{ !isset($class_id) || $class_id == 0 ? 'selected' : '' }}>Please select
                        a class
                    </option>
                    @isset($classes)
                        @foreach ($classes as $school_class)
                            <option
                                value="{{$school_class->id}}" {{ isset($class_id) && $class_id == $school_class->id ? 'selected' : '' }}>{{$school_class->class_name}}</option>
                        @endforeach
                    @endisset
                </select>
            </div>
            <div>
                <select class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 bg-white" id="section-select" name="section_id" required>
                </select>
            </div>
            <div>
                <select class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 bg-white" id="course-select" name="course_id" required>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm px-4 py-2.5 rounded-lg transition-colors">
                    <i data-lucide="refresh-cw" class="w-4 h-4"></i> Load List
                </button>
            </div>
        </div>
    </form>
</div>

<div class="bg-white rounded-card shadow-card border border-gray-200">
    <table class="w-full text-sm">
        <thead>
            <tr class="bg-gray-50 border-b border-gray-200">
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Photo</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Student Name</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Marks</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Grade Points</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Grade</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @isset($marks)
                @foreach ($marks as $mark)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-4 py-3 text-gray-400"><i data-lucide="users" class="w-5 h-5"></i></td>
                    <td class="px-4 py-3 text-gray-600">{{$mark->student->first_name}} {{$mark->student->last_name}}</td>
                    <td class="px-4 py-3 text-gray-600">{{$mark->final_marks}}</td>
                    <td class="px-4 py-3 text-gray-600">{{$mark->getAttribute('point')}}</td>
                    <td class="px-4 py-3">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-700">{{$mark->getAttribute('grade')}}</span>
                    </td>
                </tr>
                @endforeach
            @endisset
        </tbody>
    </table>
</div>
@endsection

@push('scripts')
<script>
    function getSectionsAndCourses(obj, selectedSectionId = null, selectedCourseId = null) {
        var class_id = typeof obj === 'object' ? obj.options[obj.selectedIndex].value : obj;

        var url = "{{route('get.sections.courses.by.classId')}}?class_id=" + class_id

        fetch(url)
        .then((resp) => resp.json())
        .then(function(data) {
            var sectionSelect = document.getElementById('section-select');
            sectionSelect.options.length = 0;
            data.sections.unshift({'id': 0,'section_name': 'Please select a section'})
            data.sections.forEach(function(section, key) {
                var option = new Option(section.section_name, section.id);
                if (selectedSectionId && section.id == selectedSectionId) {
                    option.selected = true;
                }
                sectionSelect[key] = option;
            });

            var courseSelect = document.getElementById('course-select');
            courseSelect.options.length = 0;
            data.courses.unshift({'id': 0,'course_name': 'Please select a course'})
            data.courses.forEach(function(course, key) {
                var option = new Option(course.course_name, course.id);
                if (selectedCourseId && course.id == selectedCourseId) {
                    option.selected = true;
                }
                courseSelect[key] = option;
            });
        })
        .catch(function(error) {
            console.log(error);
        });
    }

    @if(isset($class_id) && $class_id > 0)
    document.addEventListener('DOMContentLoaded', function () {
        getSectionsAndCourses('{{$class_id}}', '{{$section_id}}', '{{$course_id}}');
    });
    @endif
</script>
@endpush
