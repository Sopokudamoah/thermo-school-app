@extends('layouts.app')

@section('page-title', 'Edit Budget')

@section('content')

    <div class="mb-6">
        <h1 class="font-heading text-xl font-bold text-gray-900">Edit Budget: {{ $budget->fiscal_year }}</h1>
        <nav class="flex items-center gap-1.5 mt-1 text-sm text-gray-500">
            <a href="{{ route('home') }}" class="hover:text-indigo-600 transition-colors">Home</a>
            <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
            <a href="{{ route('finance.dashboard') }}" class="hover:text-indigo-600 transition-colors">Finance</a>
            <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
            <a href="{{ route('finance.budgets.index') }}" class="hover:text-indigo-600 transition-colors">Budgets</a>
            <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
            <span class="text-gray-900">Edit Budget</span>
        </nav>
    </div>

    @include('session-messages')

    <div class="bg-white rounded-card shadow-card border border-gray-200 p-6 max-w-2xl">
        <form action="{{ route('finance.budgets.update', $budget->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">Budget Name <sup
                            class="text-indigo-500">*</sup></label>
                    <input type="text" id="name" name="name" value="{{ old('name', $budget->name) }}"
                           placeholder="e.g. Annual Academic Budget 2024" required
                           class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 transition-colors">
                </div>

                <div>
                    <label for="year" class="block text-sm font-medium text-gray-700 mb-1.5">Year <sup
                            class="text-indigo-500">*</sup></label>
                    <input type="number" id="year" name="year" value="{{ old('year', $budget->year) }}"
                           placeholder="e.g. 2024" required
                           class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 transition-colors">
                </div>

                <div>
                    <label for="total_allocated" class="block text-sm font-medium text-gray-700 mb-1.5">Total Budget
                        Amount <sup class="text-indigo-500">*</sup></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 text-sm">$</span>
                        </div>
                        <input type="number" step="0.01" min="0" id="total_allocated" name="total_allocated"
                               value="{{ old('total_allocated', $budget->total_allocated) }}" placeholder="0.00"
                               required
                               class="block w-full rounded-lg border border-gray-300 pl-7 pr-3 py-2.5 text-sm text-gray-900 font-bold focus:outline-none focus:border-indigo-500 transition-colors">
                    </div>
                    <p class="mt-1.5 text-xs text-gray-500">This is the overall budget for the entire year across all
                        departments.</p>
                </div>

                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-1.5">Notes</label>
                    <textarea id="notes" name="notes" rows="3" placeholder="Enter budget notes..."
                              class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 transition-colors">{{ old('notes', $budget->notes) }}</textarea>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit"
                            class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm px-5 py-2.5 rounded-lg transition-colors shadow-sm">
                        <i data-lucide="save" class="w-4 h-4"></i> Update Budget
                    </button>
                    <a href="{{ route('finance.budgets.index') }}"
                       class="inline-flex items-center gap-2 bg-white hover:bg-gray-50 text-gray-700 font-medium text-sm px-5 py-2.5 rounded-lg border border-gray-300 transition-colors">
                        Cancel
                    </a>
                </div>
            </div>
        </form>
    </div>

@endsection
