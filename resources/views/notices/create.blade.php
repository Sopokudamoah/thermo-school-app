@extends('layouts.app')
@section('page-title', 'Create Notice')

@section('content')
<div class="mb-6">
    <h1 class="font-heading text-xl font-bold text-gray-900"><i data-lucide="megaphone" class="inline w-5 h-5 mr-2"></i> Create Notice</h1>
    <nav class="flex items-center gap-1.5 mt-1 text-sm text-gray-500">
        <a href="{{route('home')}}" class="hover:text-indigo-600">Home</a>
        <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
        <span>Create Notice</span>
    </nav>
</div>

@include('session-messages')

<div class="bg-white rounded-card shadow-card border border-gray-200 p-6">
    <form action="{{route('notice.store')}}" method="POST">
        @csrf
        <input type="hidden" name="session_id" value="{{$current_school_session_id}}">
        @include('components.ckeditor.editor', ['name' => 'notice'])
        <div class="mt-4">
            <button type="submit" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm px-4 py-2.5 rounded-lg transition-colors">
                <i data-lucide="check" class="w-4 h-4"></i> Save
            </button>
        </div>
    </form>
</div>
@endsection
