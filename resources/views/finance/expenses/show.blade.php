@extends('layouts.app')

@section('page-title', 'Expense Details')

@section('content')

    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="font-heading text-xl font-bold text-gray-900">Expense Details</h1>
            <nav class="flex items-center gap-1.5 mt-1 text-sm text-gray-500">
                <a href="{{ route('home') }}" class="hover:text-indigo-600 transition-colors">Home</a>
                <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                <a href="{{ route('finance.dashboard') }}" class="hover:text-indigo-600 transition-colors">Finance</a>
                <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                <a href="{{ route('finance.expenses.index') }}"
                   class="hover:text-indigo-600 transition-colors">Expenses</a>
                <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                <span class="text-gray-900">Details</span>
            </nav>
        </div>
        <div class="flex items-center gap-3">
            @if($expense->status == 'pending')
                @can('finance.expense.approve')
                    <form action="{{ route('finance.expenses.approve', $expense->id) }}" method="POST">
                        @csrf
                        <button type="submit"
                                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold text-white bg-emerald-600 hover:bg-emerald-700 transition-colors shadow-sm">
                            <i data-lucide="check-circle" class="w-4 h-4"></i> Approve
                        </button>
                    </form>
                    <button @click="$dispatch('open-modal', 'reject-expense')"
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold text-white bg-red-600 hover:bg-red-700 transition-colors shadow-sm">
                        <i data-lucide="x-circle" class="w-4 h-4"></i> Reject
                    </button>
                @endcan

                @can('finance.expense.edit')
                    <a href="{{ route('finance.expenses.edit', $expense->id) }}"
                       class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 transition-colors">
                        <i data-lucide="pencil" class="w-4 h-4"></i> Edit
                    </a>
                @endcan
            @endif
        </div>
    </div>

    @include('session-messages')

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-card shadow-card border border-gray-200 p-6">
                <h3 class="font-heading text-sm font-semibold text-gray-700 uppercase tracking-wider mb-4 pb-2 border-b border-gray-100">
                    Expense Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-semibold">Category</p>
                        <p class="text-sm font-bold text-gray-900">{{ $expense->category->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-semibold">Amount</p>
                        <p class="text-lg font-black text-gray-900">${{ number_format($expense->amount, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-semibold">Date</p>
                        <p class="text-sm font-medium text-gray-900">{{ $expense->expense_date->format('F d, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-semibold">Status</p>
                        @php
                            $colors = [
                                'pending' => 'bg-amber-100 text-amber-800',
                                'approved' => 'bg-emerald-100 text-emerald-800',
                                'rejected' => 'bg-red-100 text-red-800',
                            ];
                            $color = $colors[$expense->status] ?? 'bg-gray-100 text-gray-800';
                        @endphp
                        <span
                            class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $color }}">
                        {{ ucfirst($expense->status) }}
                    </span>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-xs text-gray-500 uppercase font-semibold">Description</p>
                        <p class="text-sm text-gray-700 mt-1 whitespace-pre-wrap">{{ $expense->description }}</p>
                    </div>
                    @if($expense->vendor)
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-semibold">Vendor</p>
                            <p class="text-sm font-medium text-indigo-600 hover:underline">
                                <a href="{{ route('finance.vendors.index') }}">{{ $expense->vendor->name }}</a>
                            </p>
                        </div>
                    @endif
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-semibold">Submitted By</p>
                        <p class="text-sm font-medium text-gray-900">{{ $expense->submitter->first_name ?? 'N/A' }}</p>
                    </div>
                    @if($expense->status != 'pending')
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-semibold">{{ $expense->status == 'approved' ? 'Approved' : 'Rejected' }}
                                By</p>
                            <p class="text-sm font-medium text-gray-900">{{ $expense->approver->first_name ?? 'N/A' }}</p>
                        </div>
                    @endif
                    @if($expense->rejection_reason)
                        <div class="md:col-span-2 p-3 bg-red-50 border border-red-100 rounded-lg text-red-700">
                            <p class="text-xs uppercase font-semibold mb-1">Rejection Reason</p>
                            <p class="text-sm">{{ $expense->rejection_reason }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-card shadow-card border border-gray-200 p-6">
                <h3 class="font-heading text-sm font-semibold text-gray-700 uppercase tracking-wider mb-4 pb-2 border-b border-gray-100">
                    Receipt</h3>
                @if($expense->receipt_path)
                    <div class="rounded-lg overflow-hidden border border-gray-200 mb-4">
                        @if(Str::endsWith($expense->receipt_path, '.pdf'))
                            <div class="bg-gray-50 p-10 text-center">
                                <i data-lucide="file-text" class="w-12 h-12 text-gray-400 mx-auto mb-2"></i>
                                <p class="text-xs text-gray-500">PDF Document</p>
                            </div>
                        @else
                            <img src="{{ asset('storage/' . $expense->receipt_path) }}" alt="Receipt"
                                 class="w-full h-auto">
                        @endif
                    </div>
                    <a href="{{ asset('storage/' . $expense->receipt_path) }}" target="_blank"
                       class="block w-full text-center bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2 rounded-lg transition-colors text-sm">
                        View Full Receipt
                    </a>
                @else
                    <div
                        class="py-10 text-center bg-gray-50 border-2 border-dashed border-gray-200 rounded-lg text-gray-400">
                        <i data-lucide="image-off" class="w-8 h-8 mx-auto mb-2"></i>
                        <p class="text-xs">No receipt attached</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Reject Modal --}}
    @push('modals')
        <div x-data="{}"
             x-show="activeModal === 'reject-expense'"
             class="fixed inset-0 z-50 overflow-y-auto"
             style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="closeModal()"></div>
                <div
                    class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full">
                    <form action="{{ route('finance.expenses.index') }}/{{ $expense->id }}/reject" method="POST">
                        @csrf
                        <div class="px-6 py-4 border-b border-gray-100">
                            <h3 class="text-lg font-bold text-gray-900">Reject Expense</h3>
                        </div>
                        <div class="p-6">
                            <label for="reason" class="block text-sm font-medium text-gray-700 mb-2">Reason for
                                Rejection <sup class="text-red-500">*</sup></label>
                            <textarea name="reason" id="reason" rows="3" required
                                      class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-red-500"
                                      placeholder="Please explain why this expense is being rejected..."></textarea>
                        </div>
                        <div class="px-6 py-4 bg-gray-50 flex justify-end gap-3">
                            <button type="button" @click="closeModal()"
                                    class="px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg">
                                Cancel
                            </button>
                            <button type="submit"
                                    class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg shadow-sm">
                                Confirm Rejection
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endpush

@endsection
