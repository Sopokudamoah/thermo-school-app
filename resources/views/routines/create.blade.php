@extends('layouts.app')
@section('page-title', 'Create Routine')

@section('content')
<div class="mb-6">
    <h1 class="font-heading text-xl font-bold text-gray-900"><i data-lucide="plus" class="inline w-5 h-5 mr-2"></i> Create Routine</h1>
    <nav class="flex items-center gap-1.5 mt-1 text-sm text-gray-500">
        <a href="{{route('home')}}" class="hover:text-indigo-600">Home</a>
        <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
        <span>Create Routine</span>
    </nav>
</div>

@include('session-messages')

<div class="bg-white rounded-card shadow-card border border-gray-200 p-6 max-w-lg">
    <form action="{{route('section.routine.store')}}" method="POST">
        @csrf
        <input type="hidden" name="session_id" value="{{$current_school_session_id}}">
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Select class <sup class="text-indigo-500">*</sup></label>
            <select onchange="getSectionsAndCourses(this);" class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 bg-white" name="class_id" required>
                @isset($classes)
                    <option selected disabled>Please select a class</option>
                    @foreach ($classes as $school_class)
                    <option value="{{$school_class->id}}">{{$school_class->class_name}}</option>
                    @endforeach
                @endisset
            </select>
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Select section <sup class="text-indigo-500">*</sup></label>
            <select class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 bg-white" id="section-select" name="section_id" required>
            </select>
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Select course <sup class="text-indigo-500">*</sup></label>
            <select class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 bg-white" id="course-select" name="course_id" required>
            </select>
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Week Day <sup class="text-indigo-500">*</sup></label>
            <select class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 bg-white" name="weekday" required>
                <option value="1">Monday</option>
                <option value="2">Tuesday</option>
                <option value="3">Wednesday</option>
                <option value="4">Thursday</option>
                <option value="5">Friday</option>
                <option value="6">Saturday</option>
                <option value="7">Sunday</option>
            </select>
        </div>
        <div class="mb-4">
            <label for="inputStarts" class="block text-sm font-medium text-gray-700 mb-1.5">Starts <sup class="text-indigo-500">*</sup></label>
            <input type="time" class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100" id="inputStarts" name="start" required>
        </div>
        <div class="mb-6">
            <label for="inputEnds" class="block text-sm font-medium text-gray-700 mb-1.5">Ends <sup class="text-indigo-500">*</sup></label>
            <input type="time" class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100" id="inputEnds" name="end" required>
        </div>
        <button type="submit" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm px-4 py-2.5 rounded-lg transition-colors">
            <i data-lucide="check" class="w-4 h-4"></i> Create
        </button>
    </form>
</div>
@endsection

@push('scripts')
<script>
    function getSectionsAndCourses(obj) {
        var class_id = obj.options[obj.selectedIndex].value;

        var url = "{{route('get.sections.courses.by.classId')}}?class_id=" + class_id

        fetch(url)
        .then((resp) => resp.json())
        .then(function(data) {
            var sectionSelect = document.getElementById('section-select');
            sectionSelect.options.length = 0;
            data.sections.unshift({'id': 0,'section_name': 'Please select a section'})
            data.sections.forEach(function(section, key) {
                sectionSelect[key] = new Option(section.section_name, section.id);
            });

            var courseSelect = document.getElementById('course-select');
            courseSelect.options.length = 0;
            data.courses.unshift({'id': 0,'course_name': 'Please select a course'})
            data.courses.forEach(function(course, key) {
                courseSelect[key] = new Option(course.course_name, course.id);
            });
        })
        .catch(function(error) {
            console.log(error);
        });
    }
</script>
@endpush
