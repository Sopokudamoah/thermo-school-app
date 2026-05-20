@extends('layouts.app')
@section('page-title', 'My Courses')

@section('content')
<div class="mb-6">
    <h1 class="font-heading text-xl font-bold text-gray-900"><i data-lucide="library" class="inline w-5 h-5 mr-2"></i> My Courses</h1>
    <nav class="flex items-center gap-1.5 mt-1 text-sm text-gray-500">
        <a href="{{route('home')}}" class="hover:text-indigo-600">Home</a>
        <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
        <span>My courses</span>
    </nav>
</div>

<div class="bg-white rounded-card shadow-card border border-gray-200 p-4 mb-5">
    <p class="text-sm font-medium text-gray-600 mb-3">Filter list by:</p>
    <form action="{{route('course.teacher.list.show')}}" method="GET">
        <input type="hidden" name="teacher_id" value="{{Auth::user()->id}}">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <div>
                <select class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 bg-white" name="semester_id" required>
                    @isset($semesters)
                        @foreach ($semesters as $semester)
                        <option value="{{$semester->id}}" {{($semester->id === request()->query('semester_id'))?'selected':''}}>{{$semester->semester_name}}</option>
                        @endforeach
                    @endisset
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
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Course Name</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Class</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Section</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @isset($courses)
                @foreach ($courses as $course)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-4 py-3 text-gray-600">{{$course->course->course_name}}</td>
                    <td class="px-4 py-3 text-gray-600">{{$course->schoolClass->class_name}}</td>
                    <td class="px-4 py-3 text-gray-600">{{$course->section->section_name}}</td>
                    <td class="px-4 py-3">
                        <div class="flex gap-1.5 flex-wrap">
                            <a href="{{route('attendance.create.show', [
                                'class_id' => $course->schoolClass->id,
                                'section_id' => $course->section->id,
                                'course_id' => $course->course->id,
                                'class_name' => $course->schoolClass->class_name,
                                'section_name' => $course->section->section_name,
                                'course_name' => $course->course->course_name
                            ])}}" class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 transition-colors border border-gray-200">
                                <i data-lucide="calendar-check" class="w-3.5 h-3.5"></i> Take Attendance
                            </a>
                            <a href="{{route('attendance.list.show', [
                                'class_id' => $course->schoolClass->id,
                                'section_id' => $course->section->id,
                                'course_id' => $course->course->id,
                                'class_name' => $course->schoolClass->class_name,
                                'section_name' => $course->section->section_name,
                                'course_name' => $course->course->course_name
                            ])}}" class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 transition-colors border border-gray-200">
                                <i data-lucide="calendar-check" class="w-3.5 h-3.5"></i> View Attendance
                            </a>
                            <a href="{{route('course.syllabus.index', ['course_id' => $course->course->id])}}" class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 transition-colors border border-gray-200">
                                <i data-lucide="file-text" class="w-3.5 h-3.5"></i> View Syllabus
                            </a>
                            <a href="{{route('assignment.create', [
                                'class_id' => $course->schoolClass->id,
                                'section_id' => $course->section->id,
                                'course_id' => $course->course->id,
                                'semester_id' => request()->query('semester_id')
                            ])}}" class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 transition-colors border border-gray-200">
                                <i data-lucide="file-plus" class="w-3.5 h-3.5"></i> Create Assignment
                            </a>
                            <a href="{{route('assignment.list.show', ['course_id' => $course->course->id])}}" class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 transition-colors border border-gray-200">
                                <i data-lucide="file-text" class="w-3.5 h-3.5"></i> View Assignments
                            </a>
                            <a href="{{route('course.mark.create', [
                                'class_id' => $course->schoolClass->id,
                                'class_name' => $course->schoolClass->class_name,
                                'section_id' => $course->section->id,
                                'section_name' => $course->section->section_name,
                                'course_id' => $course->course->id,
                                'course_name' => $course->course->course_name,
                                'semester_id' => $selected_semester_id
                            ])}}" class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 transition-colors border border-gray-200">
                                <i data-lucide="pencil" class="w-3.5 h-3.5"></i> Give Marks
                            </a>
                            <a href="{{route('course.mark.list.show', [
                                'class_id' => $course->schoolClass->id,
                                'class_name' => $course->schoolClass->class_name,
                                'section_id' => $course->section->id,
                                'section_name' => $course->section->section_name,
                                'course_id' => $course->course->id,
                                'course_name' => $course->course->course_name,
                                'semester_id' => $selected_semester_id
                            ])}}" class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 transition-colors border border-gray-200">
                                <i data-lucide="eye" class="w-3.5 h-3.5"></i> View Final Results
                            </a>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-medium text-gray-400 border border-gray-200 cursor-not-allowed opacity-60" aria-disabled="true">
                                Message Students
                            </span>
                        </div>
                    </td>
                </tr>
                @endforeach
            @endisset
        </tbody>
    </table>
</div>
@endsection
