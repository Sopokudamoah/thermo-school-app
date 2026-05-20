@extends('layouts.app')
@section('page-title', 'Add Grading Rule')

@section('content')
<div class="mb-6">
    <h1 class="font-heading text-xl font-bold text-gray-900"><i data-lucide="file-plus" class="inline w-5 h-5 mr-2"></i> Add Grading Rule</h1>
</div>

@include('session-messages')

<div class="bg-white rounded-card shadow-card border border-gray-200 p-6 max-w-lg">
    <form action="{{route('exam.grade.system.rule.store')}}" method="POST">
        @csrf
        <input type="hidden" name="grading_system_id" value="{{$grading_system_id}}">
        <input type="hidden" name="session_id" value="{{$current_school_session_id}}">
        <div class="mb-4">
            <label for="inputPoint" class="block text-sm font-medium text-gray-700 mb-1.5">Point <sup class="text-indigo-500">*</sup></label>
            <input type="number" step="0.01" name="point" class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100" id="inputPoint" placeholder="3.5, 4.0, ...">
        </div>
        <div class="mb-4">
            <label for="inputGrade" class="block text-sm font-medium text-gray-700 mb-1.5">Grade <sup class="text-indigo-500">*</sup></label>
            <input type="text" name="grade" class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100" id="inputGrade" placeholder="A+, A-, ...">
        </div>
        <div class="mb-4">
            <label for="inputStarts" class="block text-sm font-medium text-gray-700 mb-1.5">Starts <sup class="text-indigo-500">*</sup></label>
            <input type="number" step="0.01" name="start_at" class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100" id="inputStarts" placeholder="90, 85, ...">
        </div>
        <div class="mb-6">
            <label for="inputEnds" class="block text-sm font-medium text-gray-700 mb-1.5">Ends <sup class="text-indigo-500">*</sup></label>
            <input type="number" step="0.01" name="end_at" class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100" id="inputEnds" placeholder="100, 89, ...">
        </div>
        <button type="submit" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm px-4 py-2.5 rounded-lg transition-colors">
            <i data-lucide="plus" class="w-4 h-4"></i> Add
        </button>
    </form>
</div>
@endsection
