@extends('layouts.app')

@section('page-title', 'Edit Class')

@section('content')

<div class="mb-6">
    <h1 class="font-heading text-xl font-bold text-gray-900">Edit Class</h1>
    <nav class="flex items-center gap-1.5 mt-1 text-sm text-gray-500">
        <a href="{{ route('home') }}" class="hover:text-indigo-600 transition-colors">Home</a>
        <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
        <a href="{{ url()->previous() }}" class="hover:text-indigo-600 transition-colors">Classes</a>
        <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
        <span class="text-gray-900">Edit Class</span>
    </nav>
</div>

@include('session-messages')

<div class="bg-white rounded-card shadow-card border border-gray-200 p-6 max-w-lg">
    <form action="{{ route('school.class.update') }}" method="POST" class="space-y-4">
        @csrf
        <input type="hidden" name="session_id" value="{{ $current_school_session_id }}">
        <input type="hidden" name="class_id" value="{{ $class_id }}">

        <div>
            <label for="class_name" class="block text-sm font-medium text-gray-700 mb-1.5">Class Name</label>
            <input id="class_name" name="class_name" type="text" value="{{ $schoolClass->class_name }}"
                   class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-colors">
        </div>

        <button type="submit"
                class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm px-5 py-2.5 rounded-lg transition-colors">
            <i data-lucide="check" class="w-4 h-4"></i>
            Save
        </button>
    </form>
</div>

@endsection
