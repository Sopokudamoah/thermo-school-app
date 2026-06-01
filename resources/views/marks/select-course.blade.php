@extends('layouts.app')
@section('page-title', 'Give Marks - Select Course')

@section('content')
    <div class="mb-6">
        <h1 class="font-heading text-xl font-bold text-gray-900"><i data-lucide="table" class="inline w-5 h-5 mr-2"></i>
            Give Marks</h1>
        <nav class="flex items-center gap-1.5 mt-1 text-sm text-gray-500">
            <a href="{{route('home')}}" class="hover:text-indigo-600">Home</a>
            <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
            <span>Give Marks</span>
        </nav>
    </div>

    @include('session-messages')

    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-card shadow-card border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-6">Select Course Details</h2>

            <form action="{{ route('course.mark.create') }}" method="GET" class="space-y-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="semester_id" class="block text-sm font-medium text-gray-700 mb-1.5">Semester</label>
                        <select name="semester_id" id="semester_id" required
                                class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 bg-white">
                            <option value="" {{ !isset($academic_setting->active_semester_id) ? 'selected' : '' }}>
                                Select Semester
                            </option>
                            @foreach($semesters as $semester)
                                <option
                                    value="{{ $semester->id }}" {{ (isset($academic_setting->active_semester_id) && $academic_setting->active_semester_id == $semester->id) ? 'selected' : '' }}>{{ $semester->semester_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="class_id" class="block text-sm font-medium text-gray-700 mb-1.5">Class</label>
                        <select name="class_id" id="class_id" required
                                class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 bg-white">
                            <option value="">Select Class</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->class_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="section_id" class="block text-sm font-medium text-gray-700 mb-1.5">Section</label>
                        <select name="section_id" id="section_id" required
                                class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 bg-white">
                            <option value="">Select Section</option>
                        </select>
                    </div>

                    <div>
                        <label for="course_id" class="block text-sm font-medium text-gray-700 mb-1.5">Course</label>
                        <select name="course_id" id="course_id" required
                                class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 bg-white">
                            <option value="">Select Course</option>
                        </select>
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit"
                            class="w-full inline-flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm px-4 py-3 rounded-lg transition-colors">
                        <i data-lucide="arrow-right" class="w-4 h-4"></i> Continue to Give Marks
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function () {
                $('#class_id').on('change', function () {
                    var classId = $(this).val();
                    if (classId) {
                        $.ajax({
                            url: '{{ route("get.sections.courses.by.classId") }}',
                            type: 'GET',
                            data: {class_id: classId},
                            success: function (data) {
                                $('#section_id').empty().append('<option value="">Select Section</option>');
                                $('#course_id').empty().append('<option value="">Select Course</option>');

                                $.each(data.sections, function (key, value) {
                                    $('#section_id').append('<option value="' + value.id + '">' + value.section_name + '</option>');
                                });

                                $.each(data.courses, function (key, value) {
                                    $('#course_id').append('<option value="' + value.id + '">' + value.course_name + '</option>');
                                });

                                if (typeof lucide !== 'undefined') {
                                    lucide.createIcons();
                                }
                            }
                        });
                    } else {
                        $('#section_id').empty().append('<option value="">Select Section</option>');
                        $('#course_id').empty().append('<option value="">Select Course</option>');
                    }
                });

                $('#section_id').on('change', function () {
                    var sectionId = $(this).val();
                    var classId = $('#class_id').val();
                    if (sectionId && classId) {
                        $.ajax({
                            url: '{{ route("get.sections.courses.by.classId") }}',
                            type: 'GET',
                            data: {
                                class_id: classId,
                                section_id: sectionId
                            },
                            success: function (data) {
                                $('#course_id').empty().append('<option value="">Select Course</option>');

                                $.each(data.courses, function (key, value) {
                                    $('#course_id').append('<option value="' + value.id + '">' + value.course_name + '</option>');
                                });

                                if (typeof lucide !== 'undefined') {
                                    lucide.createIcons();
                                }
                            }
                        });
                    }
                });
            });
        </script>
    @endpush
@endsection
