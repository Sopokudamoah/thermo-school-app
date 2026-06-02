@extends('layouts.app')

@section('page-title', 'Budget Variance Report')

@section('content')

    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="font-heading text-xl font-bold text-gray-900">{{ $budget->name }} ({{ $budget->year }})</h1>
            <nav class="flex items-center gap-1.5 mt-1 text-sm text-gray-500">
                <a href="{{ route('home') }}" class="hover:text-indigo-600 transition-colors">Home</a>
                <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                <a href="{{ route('finance.dashboard') }}" class="hover:text-indigo-600 transition-colors">Finance</a>
                <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                <a href="{{ route('finance.budgets.index') }}"
                   class="hover:text-indigo-600 transition-colors">Budgets</a>
                <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                <span class="text-gray-900">Variance Report</span>
            </nav>
        </div>
        <div class="flex items-center gap-3">
            @can('finance.budget.manage')
                <button @click="$dispatch('open-modal', 'add-department')"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 transition-colors shadow-sm">
                    <i data-lucide="plus" class="w-4 h-4"></i> Add Department
                </button>
            @endcan
        </div>
    </div>

    @include('session-messages')

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <div class="bg-white p-5 rounded-card border border-gray-200 shadow-card">
            <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Total Allocated</p>
            <p class="text-2xl font-black text-gray-900">@money($budget->total_allocated)</p>
        </div>
        <div class="bg-white p-5 rounded-card border border-gray-200 shadow-card">
            <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Total Spent</p>
            <p class="text-2xl font-black text-amber-600">@money($budget->total_spent)</p>
        </div>
        <div class="bg-white p-5 rounded-card border border-gray-200 shadow-card">
            <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Remaining Budget</p>
            @php $remaining = $budget->total_allocated->subtract($budget->total_spent); @endphp
            <p class="text-2xl font-black {{ $remaining->isNegative() ? 'text-red-600' : 'text-emerald-600' }}">
                @money($remaining)
            </p>
        </div>
    </div>

    <div class="space-y-8">
        @foreach($budget->departments as $dept)
            <div class="bg-white rounded-card shadow-card border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200 flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <h3 class="font-heading text-base font-bold text-gray-900">{{ $dept->name }}</h3>
                        <span class="text-xs font-medium px-2 py-0.5 rounded-full bg-indigo-100 text-indigo-700">
                    Allocated: @money($dept->allocated)
                </span>
                    </div>
                    @can('finance.budget.manage')
                        <button @click="$dispatch('open-modal', 'add-category-{{ $dept->id }}')"
                                class="text-xs font-bold text-indigo-600 hover:text-indigo-800 uppercase tracking-wider flex items-center gap-1">
                            <i data-lucide="plus-circle" class="w-3.5 h-3.5"></i> Add Category
                        </button>
                    @endcan
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead>
                        <tr class="bg-white border-b border-gray-100">
                            <th class="px-6 py-3 font-semibold text-gray-600 uppercase tracking-tight text-[11px]">
                                Category
                            </th>
                            <th class="px-6 py-3 font-semibold text-gray-600 uppercase tracking-tight text-[11px] text-right">
                                Allocated
                            </th>
                            <th class="px-6 py-3 font-semibold text-gray-600 uppercase tracking-tight text-[11px] text-right">
                                Actual Spent
                            </th>
                            <th class="px-6 py-3 font-semibold text-gray-600 uppercase tracking-tight text-[11px] text-right">
                                Variance
                            </th>
                            <th class="px-6 py-3 font-semibold text-gray-600 uppercase tracking-tight text-[11px] text-right w-32">
                                Usage
                            </th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                        @foreach($dept->categories as $cat)
                            @php
                                $actual = $variance[$cat->expense_category_id]['spent'] ?? new \Money\Money(0, $cat->allocated->getCurrency());
                                $var_amount = $cat->allocated->subtract($actual);
                                $usage_percent = $cat->allocated->isPositive() ? (($actual->getAmount() / $cat->allocated->getAmount()) * 100) : 0;
                            @endphp
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $cat->expenseCategory->name }}</td>
                                <td class="px-6 py-4 text-right text-gray-700">
                                    @money($cat->allocated)</td>
                                <td class="px-6 py-4 text-right font-semibold text-gray-900">
                                    @money($actual)</td>
                                <td class="px-6 py-4 text-right font-bold {{ $var_amount->isNegative() ? 'text-red-600' : 'text-emerald-600' }}">
                                    {{ $var_amount->isNegative() ? '-' : '+' }}@money($var_amount->absolute())
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center gap-2">
                                        <div class="flex-1 h-2 bg-gray-100 rounded-full overflow-hidden">
                                            <div
                                                class="h-full {{ $usage_percent > 100 ? 'bg-red-500' : ($usage_percent > 80 ? 'bg-amber-500' : 'bg-indigo-500') }}"
                                                style="width: {{ min(100, $usage_percent) }}%"></div>
                                        </div>
                                        <span class="text-[10px] font-bold text-gray-500 w-8">{{ round($usage_percent) }}%</span>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        @if($dept->categories->isEmpty())
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-400 italic">No budget categories
                                    assigned to this department.
                                </td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Add Department Modal --}}
    @push('modals')
        <div x-data="{}" x-show="activeModal === 'add-department'" class="fixed inset-0 z-50 overflow-y-auto"
             style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="closeModal()"></div>
                <div
                    class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full">
                    <form action="{{ route('finance.budgets.department.add', $budget->id) }}" method="POST">
                        @csrf
                        <div class="px-6 py-4 border-b border-gray-100">
                            <h3 class="text-lg font-bold text-gray-900">Add Department to Budget</h3>
                        </div>
                        <div class="p-6 space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Department Name <sup
                                        class="text-red-500">*</sup></label>
                                <input type="text" name="name" required
                                       class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm"
                                       placeholder="e.g. IT Department">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Allocated Amount <sup
                                        class="text-red-500">*</sup></label>
                                <input type="number" step="0.01" min="0" name="allocated" required
                                       class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm"
                                       placeholder="0.00">
                            </div>
                        </div>
                        <div class="px-6 py-4 bg-gray-50 flex justify-end gap-3">
                            <button type="button" @click="closeModal()"
                                    class="px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg">
                                Cancel
                            </button>
                            <button type="submit"
                                    class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow-sm">
                                Add Department
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @foreach($budget->departments as $dept)
            <div x-data="{}" x-show="activeModal === 'add-category-{{ $dept->id }}'"
                 class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
                <div class="flex items-center justify-center min-h-screen px-4">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="closeModal()"></div>
                    <div
                        class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full">
                        <form action="{{ route('finance.budgets.category.add', $dept->id) }}" method="POST">
                            @csrf
                            <div class="px-6 py-4 border-b border-gray-100">
                                <h3 class="text-lg font-bold text-gray-900">Add Category to {{ $dept->name }}</h3>
                            </div>
                            <div class="p-6 space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Expense Category <sup
                                            class="text-red-500">*</sup></label>
                                    <select name="expense_category_id" required
                                            class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm bg-white">
                                        <option value="">Select Category</option>
                                        @foreach(\App\Models\Finance\ExpenseCategory::where('active', true)->get() as $ec)
                                            <option value="{{ $ec->id }}">{{ $ec->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Allocated Amount <sup
                                            class="text-red-500">*</sup></label>
                                    <input type="number" step="0.01" min="0" name="allocated" required
                                           class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm"
                                           placeholder="0.00">
                                </div>
                            </div>
                            <div class="px-6 py-4 bg-gray-50 flex justify-end gap-3">
                                <button type="button" @click="closeModal()"
                                        class="px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg">
                                    Cancel
                                </button>
                                <button type="submit"
                                        class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow-sm">
                                    Add Category
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    @endpush

@endsection
