@extends('layouts.app')

@section('page-title', 'Expenses')

@section('content')

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="font-heading text-xl font-bold text-gray-900">Expenses</h1>
            <nav class="flex items-center gap-1.5 mt-1 text-sm text-gray-500">
                <a href="{{ route('home') }}" class="hover:text-indigo-600 transition-colors">Home</a>
                <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                <a href="{{ route('finance.dashboard') }}" class="hover:text-indigo-600 transition-colors">Finance</a>
                <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                <span class="text-gray-900">Expenses</span>
            </nav>
        </div>
        @can('finance.expense.create')
            <a href="{{ route('finance.expenses.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 transition-colors shadow-sm">
                <i data-lucide="plus" class="w-4 h-4"></i> New Expense
            </a>
        @endcan
    </div>

    @include('session-messages')

    <div class="bg-white rounded-card shadow-card border border-gray-200 overflow-hidden">
        <div class="p-4 bg-gray-50 border-b border-gray-200">
            <form id="filter-form" class="flex flex-wrap items-center gap-4">
                <div>
                    <select name="category_id" id="category_id"
                            class="block w-full rounded-lg border border-gray-300 px-3 py-1.5 text-sm focus:outline-none focus:border-indigo-500">
                        <option value="">All Categories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <select name="status" id="status"
                            class="block w-full rounded-lg border border-gray-300 px-3 py-1.5 text-sm focus:outline-none focus:border-indigo-500">
                        <option value="">All Statuses</option>
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>
                <button type="submit" class="text-indigo-600 hover:text-indigo-800 font-semibold text-sm">Filter
                </button>
                <button type="reset" id="reset-filters" class="text-gray-500 hover:text-gray-700 text-sm">Clear</button>
            </form>
        </div>

        <div class="p-6">
            <div class="overflow-x-auto dt-tailwind-container">
                {{ $dataTable->table(['class' => 'w-full text-sm data-table']) }}
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
    <script>
        $(function () {
            const table = window.LaravelDataTables["expenses-table"];

            $('#filter-form').on('submit', function (e) {
                e.preventDefault();
                table.draw();
            });

            $('#reset-filters').on('click', function () {
                $('#filter-form')[0].reset();
                table.draw();
            });

            table.on('preXhr.dt', function (e, settings, data) {
                data.category_id = $('#category_id').val();
                data.status = $('#status').val();
            });
        });
    </script>
@endpush
