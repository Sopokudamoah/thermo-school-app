@extends('layouts.app')
@section('page-title', 'Edit Course')

@section('content')
<div class="mb-6">
    <h1 class="font-heading text-xl font-bold text-gray-900"><i data-lucide="pencil" class="inline w-5 h-5 mr-2"></i> Edit Course</h1>
    <nav class="flex items-center gap-1.5 mt-1 text-sm text-gray-500">
        <a href="{{route('home')}}" class="hover:text-indigo-600">Home</a>
        <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
        <a href="{{url()->previous()}}" class="hover:text-indigo-600">Courses</a>
        <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
        <span>Edit Course</span>
    </nav>
</div>

@include('session-messages')

<div class="bg-white rounded-card shadow-card border border-gray-200 p-6 max-w-lg">
    <form action="{{route('school.course.update')}}" method="POST">
        @csrf
        <input type="hidden" name="session_id" value="{{$current_school_session_id}}">
        <input type="hidden" name="course_id" value="{{$course_id}}">
        <div class="mb-4">
            <label for="course_name" class="block text-sm font-medium text-gray-700 mb-1.5">Course Name</label>
            <input class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100" id="course_name" name="course_name" type="text" value="{{$course->course_name}}">
        </div>
        <div class="mb-6">
            <label for="course_type" class="block text-sm font-medium text-gray-700 mb-1.5">Course Type</label>
            <select class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 bg-white" id="course_type" name="course_type" aria-label="Course type">
                <option value="Core" {{($course->course_type == 'Core')? 'selected' : ''}}>Core</option>
                <option value="General" {{($course->course_type == 'General')? 'selected' : ''}}>General</option>
                <option value="Elective" {{($course->course_type == 'Elective')? 'selected' : ''}}>Elective</option>
                <option value="Optional" {{($course->course_type == 'Optional')? 'selected' : ''}}>Optional</option>
            </select>
        </div>
        <button type="submit" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm px-4 py-2.5 rounded-lg transition-colors">
            <i data-lucide="check" class="w-4 h-4"></i> Save
        </button>
    </form>
</div>
@endsection
