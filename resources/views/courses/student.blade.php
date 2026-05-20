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

<div class="bg-white rounded-card shadow-card border border-gray-200">
    <table class="w-full text-sm">
        <thead>
            <tr class="bg-gray-50 border-b border-gray-200">
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Course Name</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @isset($courses)
                @foreach ($courses as $course)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-4 py-3 text-gray-600">{{$course->course_name}}</td>
                    <td class="px-4 py-3">
                        <div class="flex gap-2 flex-wrap">
                            <a href="{{route('course.mark.show', [
                                'course_id' => $course->id,
                                'course_name' => $course->course_name,
                                'semester_id' => $course->semester_id,
                                'class_id'  => $class_info->class_id,
                                'session_id' => $course->session_id,
                                'section_id' => $class_info->section_id,
                                'student_id' => Auth::user()->id
                                ])}}" class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 transition-colors border border-gray-200">
                                <i data-lucide="table" class="w-3.5 h-3.5"></i> View Marks
                            </a>
                            <a href="{{route('course.syllabus.index', ['course_id'  => $course->id])}}" class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 transition-colors border border-gray-200">
                                <i data-lucide="file-text" class="w-3.5 h-3.5"></i> View Syllabus
                            </a>
                            <a href="{{route('assignment.list.show', ['course_id' => $course->id])}}" class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 transition-colors border border-gray-200">
                                <i data-lucide="file-text" class="w-3.5 h-3.5"></i> View Assignments
                            </a>
                        </div>
                    </td>
                </tr>
                @endforeach
            @endisset
        </tbody>
    </table>
</div>
@endsection
