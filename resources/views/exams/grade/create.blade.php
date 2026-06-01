@extends('layouts.app')
@section('page-title', 'Create Grading System')

@section('content')
<div class="mb-6">
    <h1 class="font-heading text-xl font-bold text-gray-900"><i data-lucide="file-plus" class="inline w-5 h-5 mr-2"></i> Create Grading System</h1>
</div>

@include('session-messages')

<div class="bg-white rounded-card shadow-card border border-gray-200 p-6 max-w-lg">
    <form action="{{route('exam.grade.system.store')}}" method="POST">
        @csrf
        <input type="hidden" name="session_id" value="{{$current_school_session_id}}">
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Select class <sup class="text-indigo-500">*</sup></label>
            <select class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 bg-white" name="class_id" required>
                @isset($school_classes)
                    @foreach ($school_classes as $school_class)
                    <option value="{{$school_class->id}}">{{$school_class->class_name}}</option>
                    @endforeach
                @endisset
            </select>
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Select semester <sup class="text-indigo-500">*</sup></label>
            <select class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 bg-white" name="semester_id" required>
                @isset($semesters)
                    @foreach ($semesters as $semester)
                        <option
                            value="{{$semester->id}}" {{ (isset($academic_setting->active_semester_id) && $academic_setting->active_semester_id == $semester->id) || ($semester->id === request()->query('semester_id')) ? 'selected' : '' }}>{{$semester->semester_name}}</option>
                    @endforeach
                @endisset
            </select>
        </div>
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Grading System name <sup class="text-indigo-500">*</sup></label>
            <input type="text" class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100" placeholder="Grading System 1" name="system_name" required>
        </div>
        <button type="submit" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm px-4 py-2.5 rounded-lg transition-colors">
            <i data-lucide="check" class="w-4 h-4"></i> Create
        </button>
    </form>
</div>
@endsection
