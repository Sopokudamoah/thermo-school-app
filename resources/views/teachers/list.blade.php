@extends('layouts.app')

@section('page-title', 'Teacher List')

@section('content')

<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="font-heading text-xl font-bold text-gray-900">Teacher List</h1>
        <nav class="flex items-center gap-1.5 mt-1 text-sm text-gray-500">
            <a href="{{ route('home') }}" class="hover:text-indigo-600 transition-colors">Home</a>
            <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
            <span class="text-gray-900">Teacher List</span>
        </nav>
    </div>
    @if (!session()->has('browse_session_id') && Auth::user()->role == "admin")
    <a href="{{ route('teacher.create.show') }}"
       class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm px-4 py-2.5 rounded-lg transition-colors">
        <i data-lucide="user-plus" class="w-4 h-4"></i>
        Add Teacher
    </a>
    @endif
</div>

<div class="bg-white rounded-card shadow-card border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm data-table">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200">
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Teacher</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Phone</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($teachers as $teacher)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            @if (isset($teacher->photo))
                                <img src="{{ asset('/storage'.$teacher->photo) }}"
                                     class="w-8 h-8 rounded-full object-cover shrink-0" alt="Profile picture">
                            @else
                                <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center shrink-0">
                                    <i data-lucide="user" class="w-4 h-4 text-emerald-600"></i>
                                </div>
                            @endif
                            <span class="font-medium text-gray-900">{{ $teacher->first_name }} {{ $teacher->last_name }}</span>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-gray-600">{{ $teacher->email }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $teacher->phone }}</td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-end gap-1">
                            <a href="{{ url('teachers/view/profile/'.$teacher->id) }}"
                               class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 transition-colors border border-gray-200">
                                <i data-lucide="eye" class="w-3.5 h-3.5"></i> Profile
                            </a>
                            @can('edit users')
                            <a href="{{ route('teacher.edit.show', ['id' => $teacher->id]) }}"
                               class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 transition-colors border border-gray-200">
                                <i data-lucide="pencil" class="w-3.5 h-3.5"></i> Edit
                            </a>
                            @endcan
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-4 py-12 text-center">
                        <div class="flex flex-col items-center gap-2">
                            <i data-lucide="user-check" class="w-8 h-8 text-gray-300"></i>
                            <p class="text-sm text-gray-500">No teachers found.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
