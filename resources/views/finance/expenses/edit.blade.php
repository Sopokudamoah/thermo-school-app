@extends('layouts.app')

@section('page-title', 'Edit Expense')

@section('content')

    <div class="mb-6">
        <h1 class="font-heading text-xl font-bold text-gray-900">Edit Expense</h1>
        <nav class="flex items-center gap-1.5 mt-1 text-sm text-gray-500">
            <a href="{{ route('home') }}" class="hover:text-indigo-600 transition-colors">Home</a>
            <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
            <a href="{{ route('finance.dashboard') }}" class="hover:text-indigo-600 transition-colors">Finance</a>
            <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
            <a href="{{ route('finance.expenses.index') }}" class="hover:text-indigo-600 transition-colors">Expenses</a>
            <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
            <span class="text-gray-900">Edit Expense</span>
        </nav>
    </div>

    @include('session-messages')

    <div class="bg-white rounded-card shadow-card border border-gray-200 p-6 max-w-4xl">
        <form action="{{ route('finance.expenses.update', $expense->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1.5">Category <sup
                            class="text-indigo-500">*</sup></label>
                    <select id="category_id" name="category_id" required
                            class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 bg-white focus:outline-none focus:border-indigo-500 transition-colors">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option
                                value="{{ $category->id }}" {{ old('category_id', $expense->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="vendor_id" class="block text-sm font-medium text-gray-700 mb-1.5">Vendor
                        (Optional)</label>
                    <select id="vendor_id" name="vendor_id"
                            class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 bg-white focus:outline-none focus:border-indigo-500 transition-colors">
                        <option value="">Select Vendor</option>
                        @foreach($vendors as $vendor)
                            <option
                                value="{{ $vendor->id }}" {{ old('vendor_id', $expense->vendor_id) == $vendor->id ? 'selected' : '' }}>{{ $vendor->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="expense_date" class="block text-sm font-medium text-gray-700 mb-1.5">Expense Date <sup
                            class="text-indigo-500">*</sup></label>
                    <input type="date" id="expense_date" name="expense_date"
                           value="{{ old('expense_date', $expense->expense_date->format('Y-m-d')) }}" required
                           class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 transition-colors">
                </div>

                <div>
                    <label for="amount" class="block text-sm font-medium text-gray-700 mb-1.5">Amount <sup
                            class="text-indigo-500">*</sup></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 text-sm">$</span>
                        </div>
                        <input type="number" step="0.01" min="0" id="amount" name="amount"
                               value="{{ old('amount', $expense->amount) }}" placeholder="0.00" required
                               class="block w-full rounded-lg border border-gray-300 pl-7 pr-3 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 transition-colors">
                    </div>
                </div>
            </div>

            <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1.5">Description <sup
                        class="text-indigo-500">*</sup></label>
                <textarea id="description" name="description" rows="3" placeholder="Describe the expense..." required
                          class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 transition-colors">{{ old('description', $expense->description) }}</textarea>
            </div>

            <div class="mb-8">
                <label for="receipt" class="block text-sm font-medium text-gray-700 mb-1.5">Update Receipt Attachment
                    (Optional)</label>
                @if($expense->receipt_path)
                    <p class="text-xs text-indigo-600 mb-2 font-semibold">Current
                        file: {{ basename($expense->receipt_path) }}</p>
                @endif
                <input type="file" id="receipt" name="receipt"
                       class="block w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100">
                <p class="mt-2 text-xs text-gray-500">Supported formats: JPG, PNG, PDF (Max 2MB)</p>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit"
                        class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm px-5 py-2.5 rounded-lg transition-colors shadow-sm">
                    <i data-lucide="save" class="w-4 h-4"></i> Update Expense
                </button>
                <a href="{{ route('finance.expenses.index') }}"
                   class="inline-flex items-center gap-2 bg-white hover:bg-gray-50 text-gray-700 font-medium text-sm px-5 py-2.5 rounded-lg border border-gray-300 transition-colors">
                    Cancel
                </a>
            </div>
        </form>
    </div>

@endsection
