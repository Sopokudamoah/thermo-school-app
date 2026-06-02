@extends('layouts.app')

@section('page-title', 'Roles & Permissions')

@section('content')

    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="font-heading text-xl font-bold text-gray-900">Roles & Permissions</h1>
            <nav class="flex items-center gap-1.5 mt-1 text-sm text-gray-500">
                <a href="{{ route('home') }}" class="hover:text-indigo-600 transition-colors">Home</a>
                <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                <span class="text-gray-900">Roles</span>
            </nav>
        </div>
        <a href="{{ route('roles.create') }}"
           class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm px-4 py-2.5 rounded-lg transition-colors">
            <i data-lucide="plus-circle" class="w-4 h-4"></i>
            Add Role
        </a>
    </div>

    <div class="bg-white rounded-card shadow-card border border-gray-200 overflow-hidden p-6">
        <div class="overflow-x-auto dt-tailwind-container">
            {{ $dataTable->table(['class' => 'w-full text-sm data-table']) }}
        </div>
    </div>

    @push('scripts')
        {{ $dataTable->scripts() }}
    @endpush

@endsection
