@extends('layouts.app')
@section('page-title', 'Create Assignment')

@section('content')
<div class="mb-6">
    <h1 class="font-heading text-xl font-bold text-gray-900"><i data-lucide="file-plus" class="inline w-5 h-5 mr-2"></i> Create Assignment</h1>
    <nav class="flex items-center gap-1.5 mt-1 text-sm text-gray-500">
        <a href="{{route('home')}}" class="hover:text-indigo-600">Home</a>
        <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
        <a href="{{url()->previous()}}" class="hover:text-indigo-600">My Courses</a>
        <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
        <span>Create Assignment</span>
    </nav>
</div>

@include('session-messages')

<div class="bg-white rounded-card shadow-card border border-gray-200 p-6 max-w-lg">
    <form action="{{route('assignment.store')}}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="session_id" value="{{$current_school_session_id}}">
        <input type="hidden" name="class_id" value="{{request()->query('class_id')}}">
        <input type="hidden" name="semester_id" value="{{request()->query('semester_id')}}">
        <input type="hidden" name="course_id" value="{{request()->query('course_id')}}">
        <input type="hidden" name="section_id" value="{{request()->query('section_id')}}">
        <div class="mb-4">
            <label for="assignment-name" class="block text-sm font-medium text-gray-700 mb-1.5">Assignment Name</label>
            <input type="text" class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100" id="assignment-name" name="assignment_name" placeholder="Assignment Name" required>
        </div>
        <div class="mb-6">
            <label for="assignment-file" class="block text-sm font-medium text-gray-700 mb-1.5">Assignment File</label>
            <input type="file" name="file" class="block w-full text-sm text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" id="assignment-file" accept=".jpg,.jpeg,.bmp,.png,.gif,.doc,.docx,.csv,.rtf,.xlsx,.xls,.txt,.pdf,.zip" required>
        </div>
        <button type="submit" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm px-4 py-2.5 rounded-lg transition-colors">
            <i data-lucide="check" class="w-4 h-4"></i> Create
        </button>
    </form>
</div>
@endsection
