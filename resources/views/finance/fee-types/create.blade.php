@extends('layouts.app')

@section('page-title', 'Add Fee Type')

@section('content')

    <div class="mb-6">
        <h1 class="font-heading text-xl font-bold text-gray-900">Add Fee Type</h1>
        <nav class="flex items-center gap-1.5 mt-1 text-sm text-gray-500">
            <a href="{{ route('home') }}" class="hover:text-indigo-600 transition-colors">Home</a>
            <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
            <a href="{{ route('finance.dashboard') }}" class="hover:text-indigo-600 transition-colors">Finance</a>
            <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
            <a href="{{ route('finance.fee-types.index') }}" class="hover:text-indigo-600 transition-colors">Fee
                Types</a>
            <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
            <span class="text-gray-900">Add Fee Type</span>
        </nav>
    </div>

    @include('session-messages')

    <div class="bg-white rounded-card shadow-card border border-gray-200 p-6 max-w-2xl">
        <form action="{{ route('finance.fee-types.store') }}" method="POST">
            @csrf

            <div class="space-y-4 mb-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">Name <sup
                            class="text-indigo-500">*</sup></label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="e.g. Tuition Fee"
                           required
                           class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-colors">
                </div>

                <div>
                    <label for="code" class="block text-sm font-medium text-gray-700 mb-1.5">Code <sup
                            class="text-indigo-500">*</sup></label>
                    <input type="text" id="code" name="code" value="{{ old('code') }}" placeholder="e.g. TUITION"
                           required
                           class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-colors">
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1.5">Description</label>
                    <textarea id="description" name="description" rows="3" placeholder="Enter fee type description..."
                              class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-colors">{{ old('description') }}</textarea>
                </div>

                <div class="flex items-center gap-6">
                    <div class="flex items-center">
                        <input id="recurring" name="recurring" type="checkbox" value="1"
                               {{ old('recurring') ? 'checked' : '' }}
                               class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        <label for="recurring" class="ml-2 block text-sm text-gray-700">Recurring</label>
                    </div>

                    <div class="flex items-center">
                        <input id="active" name="active" type="checkbox" value="1"
                               {{ old('active', 1) ? 'checked' : '' }}
                               class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        <label for="active" class="ml-2 block text-sm text-gray-700">Active</label>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit"
                        class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm px-5 py-2.5 rounded-lg transition-colors shadow-sm">
                    <i data-lucide="save" class="w-4 h-4"></i> Save Fee Type
                </button>
                <a href="{{ route('finance.fee-types.index') }}"
                   class="inline-flex items-center gap-2 bg-white hover:bg-gray-50 text-gray-700 font-medium text-sm px-5 py-2.5 rounded-lg border border-gray-300 transition-colors">
                    Cancel
                </a>
            </div>
        </form>
    </div>

@endsection
