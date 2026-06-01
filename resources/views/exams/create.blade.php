@extends('layouts.app')
@section('page-title', 'Create Exam')

@section('content')
<div class="mb-6">
    <h1 class="font-heading text-xl font-bold text-gray-900"><i data-lucide="file-plus" class="inline w-5 h-5 mr-2"></i> Create Exam</h1>
    <nav class="flex items-center gap-1.5 mt-1 text-sm text-gray-500">
        <a href="{{route('home')}}" class="hover:text-indigo-600">Home</a>
        <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
        <span>Create Exam</span>
    </nav>
</div>

@include('session-messages')

<div class="bg-white rounded-card shadow-card border border-gray-200 p-6 max-w-lg">
    <form action="{{route('exam.create')}}" method="POST">
        @csrf
        <input type="hidden" name="session_id" value="{{$current_school_session_id}}">
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Select Semester <sup class="text-indigo-500">*</sup></label>
            <select class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 bg-white" name="semester_id">
                @isset($semesters)
                    @foreach ($semesters as $semester)
                        <option
                            value="{{$semester->id}}" {{ isset($academic_setting->active_semester_id) && $academic_setting->active_semester_id == $semester->id ? 'selected' : '' }}>{{$semester->semester_name}}</option>
                    @endforeach
                @endisset
            </select>
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Select class <sup class="text-indigo-500">*</sup></label>
            <select onchange="getCourses(this);" class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 bg-white" name="class_id">
                @isset($classes)
                    <option selected disabled>Please select a class</option>
                    @foreach ($classes as $school_class)
                    <option value="{{$school_class->id}}">{{$school_class->class_name}}</option>
                    @endforeach
                @endisset
            </select>
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Select course <sup class="text-indigo-500">*</sup></label>
            <select class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 bg-white" id="course-select" name="course_id">
            </select>
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Exam name <sup class="text-indigo-500">*</sup></label>
            <input type="text" class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100" name="exam_name" placeholder="Quiz, Assignment, Mid term, Final, ...">
        </div>
        <div class="mb-4">
            <label for="inputStarts" class="block text-sm font-medium text-gray-700 mb-1.5">Starts <sup class="text-indigo-500">*</sup></label>
            <input type="datetime-local" class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100" id="inputStarts" name="start_date">
        </div>
        <div class="mb-6">
            <label for="inputEnds" class="block text-sm font-medium text-gray-700 mb-1.5">Ends <sup class="text-indigo-500">*</sup></label>
            <input type="datetime-local" class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100" id="inputEnds" name="end_date">
        </div>
        <button type="submit" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm px-4 py-2.5 rounded-lg transition-colors">
            <i data-lucide="check" class="w-4 h-4"></i> Create
        </button>
    </form>
</div>
@endsection

@push('scripts')
<script>
    function getCourses(obj) {
        var class_id = obj.options[obj.selectedIndex].value;

        var url = "{{route('get.sections.courses.by.classId')}}?class_id=" + class_id

        fetch(url)
        .then((resp) => resp.json())
        .then(function(data) {

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
