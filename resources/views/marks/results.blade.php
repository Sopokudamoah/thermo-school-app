@extends('layouts.app')
@section('page-title', 'View Results')

@section('content')
<div class="mb-6">
    <h1 class="font-heading text-xl font-bold text-gray-900"><i data-lucide="table" class="inline w-5 h-5 mr-2"></i> View Results</h1>
</div>

<div class="bg-white rounded-card shadow-card border border-gray-200 p-4 mb-5">
    <p class="text-sm font-medium text-gray-600 mb-3">Filter list by:</p>
    <form action="{{route('course.mark.list.show')}}" method="GET">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <div>
                <select class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 bg-white" name="semester_id" required>
                    @isset($semesters)
                        @foreach ($semesters as $semester)
                        <option value="{{$semester->id}}">{{$semester->semester_name}}</option>
                        @endforeach
                    @endisset
                </select>
            </div>
            <div>
                <select onchange="getSectionsAndCourses(this);" class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 bg-white" name="class_id" aria-label="Class">
                    @isset($classes)
                        <option selected disabled>Please select a class</option>
                        @foreach ($classes as $school_class)
                            <option value="{{$school_class->id}}">{{$school_class->class_name}}</option>
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
