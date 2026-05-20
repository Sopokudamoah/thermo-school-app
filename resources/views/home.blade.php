@extends('layouts.app')

@section('page-title', 'Dashboard')

@section('content')

{{-- Stat cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">

    {{-- Students --}}
    <div class="bg-white rounded-card shadow-card border border-gray-200 p-5 flex items-start justify-between">
        <div>
            <p class="text-sm text-gray-500 mb-1">Total Students</p>
            <p class="text-3xl font-heading font-bold text-gray-900">{{ $studentCount }}</p>
        </div>
        <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center shrink-0">
            <i data-lucide="users" class="w-5 h-5 text-indigo-600"></i>
        </div>
    </div>

    {{-- Teachers --}}
    <div class="bg-white rounded-card shadow-card border border-gray-200 p-5 flex items-start justify-between">
        <div>
            <p class="text-sm text-gray-500 mb-1">Total Teachers</p>
            <p class="text-3xl font-heading font-bold text-gray-900">{{ $teacherCount }}</p>
        </div>
        <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center shrink-0">
            <i data-lucide="user-check" class="w-5 h-5 text-emerald-600"></i>
        </div>
    </div>

    {{-- Classes --}}
    <div class="bg-white rounded-card shadow-card border border-gray-200 p-5 flex items-start justify-between">
        <div>
            <p class="text-sm text-gray-500 mb-1">Total Classes</p>
            <p class="text-3xl font-heading font-bold text-gray-900">{{ $classCount }}</p>
        </div>
        <div class="w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center shrink-0">
            <i data-lucide="git-branch" class="w-5 h-5 text-amber-600"></i>
        </div>
    </div>

</div>

{{-- Gender distribution bar --}}
@if($studentCount > 0)
<div class="bg-white rounded-card shadow-card border border-gray-200 p-5 mb-6">
    <div class="flex items-center justify-between mb-3">
        <p class="text-sm font-medium text-gray-700">Student Gender Distribution</p>
        <div class="flex items-center gap-3 text-xs text-gray-500">
            <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-blue-700 inline-block"></span> Male</span>
            <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-blue-400 inline-block"></span> Female</span>
        </div>
    </div>
    <div class="flex rounded-full overflow-hidden h-3 w-full">
        <div class="bg-blue-700 transition-all"
             style="width: {{ round(($maleStudentsBySession/$studentCount), 2) * 100 }}%"
             title="Male: {{ round(($maleStudentsBySession/$studentCount), 2) * 100 }}%"></div>
        <div class="bg-blue-400 transition-all"
             style="width: {{ round((($studentCount - $maleStudentsBySession)/$studentCount), 2) * 100 }}%"
             title="Female: {{ round((($studentCount - $maleStudentsBySession)/$studentCount), 2) * 100 }}%"></div>
    </div>
    <div class="flex justify-between mt-2 text-xs text-gray-500">
        <span>Male: {{ round(($maleStudentsBySession/$studentCount), 2) * 100 }}%</span>
        <span>Female: {{ round((($studentCount - $maleStudentsBySession)/$studentCount), 2) * 100 }}%</span>
    </div>
</div>
@endif

{{-- Events + Notices two-column --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- Events calendar --}}
    <div class="bg-white rounded-card shadow-card border border-gray-200 overflow-hidden">
        <div class="px-5 py-3.5 border-b border-gray-100 flex items-center gap-2">
            <i data-lucide="calendar-days" class="w-4 h-4 text-indigo-600"></i>
            <h3 class="text-sm font-semibold text-gray-900">Events</h3>
        </div>
        <div class="p-4">
            @include('components.events.event-calendar', ['editable' => 'false', 'selectable' => 'false'])
        </div>
    </div>

    {{-- Notices accordion --}}
    <div class="bg-white rounded-card shadow-card border border-gray-200 overflow-hidden">
        <div class="px-5 py-3.5 border-b border-gray-100 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i data-lucide="megaphone" class="w-4 h-4 text-indigo-600"></i>
                <h3 class="text-sm font-semibold text-gray-900">Notices</h3>
            </div>
            <div class="text-xs">{{ $notices->links() }}</div>
        </div>

        @isset($notices)
        @if(count($notices) > 0)
        <div x-data="{ openItem: {{ $notices->first()?->id ?? 'null' }} }">
            @foreach ($notices as $notice)
            <div class="border-b border-gray-100 last:border-0">
                <button @click="openItem = openItem === {{ $notice->id }} ? null : {{ $notice->id }}"
                        class="w-full flex items-center justify-between px-5 py-3 text-left text-sm text-gray-800 hover:bg-gray-50 transition-colors">
                    <span class="font-medium">Published {{ $notice->created_at->diffForHumans() }}</span>
                    <i data-lucide="chevron-down" class="w-4 h-4 text-gray-400 shrink-0 transition-transform duration-200"
                       :class="openItem === {{ $notice->id }} ? 'rotate-180' : ''"></i>
                </button>
                <div x-show="openItem === {{ $notice->id }}"
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0 -translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="px-5 pb-4 text-sm text-gray-700 prose prose-sm max-w-none overflow-auto max-h-48">
                    {!! Purify::clean($notice->notice) !!}
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="flex flex-col items-center justify-center py-10 text-center">
            <i data-lucide="bell-off" class="w-8 h-8 text-gray-300 mb-2"></i>
            <p class="text-sm text-gray-500">No notices yet.</p>
        </div>
        @endif
        @endisset
    </div>

</div>

@endsection
