@extends('layouts.app')
@section('page-title', 'Create Syllabus')

@section('content')
<div class="mb-6">
    <h1 class="font-heading text-xl font-bold text-gray-900"><i data-lucide="book-marked" class="inline w-5 h-5 mr-2"></i> Create Syllabus</h1>
    <nav class="flex items-center gap-1.5 mt-1 text-sm text-gray-500">
        <a href="{{route('home')}}" class="hover:text-indigo-600">Home</a>
        <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
        <span>Create Syllabus</span>
    </nav>
</div>

@include('session-messages')

<div class="bg-white rounded-card shadow-card border border-gray-200 p-6 max-w-lg">
    <form action="{{route('syllabus.store')}}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="session_id" value="{{$current_school_session_id}}">
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Add Syllabus to class:</label>
            <select onchange="getCourses(this);" class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 bg-white" name="class_id" required>
                @isset($school_classes)
                    <option selected disabled>Please select a class</option>
                    @foreach ($school_classes as $school_class)
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
            <label for="syllabus-name" class="block text-sm font-medium text-gray-700 mb-1.5">Syllabus Name</label>
            <input type="text" class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100" id="syllabus-name" name="syllabus_name" placeholder="Syllabus Name" required>
        </div>
        <div class="mb-6">
            <label for="syllabus-file" class="block text-sm font-medium text-gray-700 mb-1.5">Syllabus File</label>
            <input type="file" name="file" class="block w-full text-sm text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" id="syllabus-file" accept=".jpg,.jpeg,.bmp,.png,.gif,.doc,.docx,.csv,.rtf,.xlsx,.xls,.txt,.pdf,.zip" required>
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
