@extends('layouts.app')
@section('page-title', 'Edit Section')

@section('content')
<div class="mb-6">
    <h1 class="font-heading text-xl font-bold text-gray-900"><i data-lucide="settings" class="inline w-5 h-5 mr-2"></i> Edit Section</h1>
</div>

@include('session-messages')

<div class="bg-white rounded-card shadow-card border border-gray-200 p-6 max-w-lg">
    <form action="{{route('school.section.update')}}" method="POST">
        @csrf
        <input type="hidden" name="session_id" value="{{$current_school_session_id}}">
        <input type="hidden" name="section_id" value="{{$section_id}}">
        <div class="mb-4">
            <label for="section_name" class="block text-sm font-medium text-gray-700 mb-1.5">Section Name</label>
            <input class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100" id="section_name" name="section_name" type="text" value="{{$section->section_name}}">
        </div>
        <div class="mb-6">
            <label for="room_no" class="block text-sm font-medium text-gray-700 mb-1.5">Room number</label>
            <input class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100" id="room_no" name="room_no" type="text" value="{{$section->room_no}}">
        </div>
        <button type="submit" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm px-4 py-2.5 rounded-lg transition-colors">
            <i data-lucide="check" class="w-4 h-4"></i> Save
        </button>
    </form>
</div>
@endsection
