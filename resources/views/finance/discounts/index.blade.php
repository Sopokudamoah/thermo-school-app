@extends('layouts.app')

@section('page-title', 'Discounts')

@section('content')

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="font-heading text-xl font-bold text-gray-900">Discounts</h1>
            <nav class="flex items-center gap-1.5 mt-1 text-sm text-gray-500">
                <a href="{{ route('home') }}" class="hover:text-indigo-600 transition-colors">Home</a>
                <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                <a href="{{ route('finance.dashboard') }}" class="hover:text-indigo-600 transition-colors">Finance</a>
                <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                <span class="text-gray-900">Discounts</span>
            </nav>
        </div>
        @can('finance.discount.create')
            <a href="{{ route('finance.discounts.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 transition-colors shadow-sm">
                <i data-lucide="plus" class="w-4 h-4"></i> Add Discount
            </a>
        @endcan
    </div>

    @include('session-messages')

    <div class="bg-white rounded-card shadow-card border border-gray-200 overflow-hidden p-6">
        <div class="overflow-x-auto dt-tailwind-container">
            {{ $dataTable->table(['class' => 'w-full text-sm data-table']) }}
        </div>
    </div>

@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush
