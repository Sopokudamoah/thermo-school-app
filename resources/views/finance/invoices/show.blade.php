@extends('layouts.app')

@section('page-title', 'Invoice Details')

@section('content')

    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="font-heading text-xl font-bold text-gray-900">Invoice: {{ $invoice->invoice_number }}</h1>
            <nav class="flex items-center gap-1.5 mt-1 text-sm text-gray-500">
                <a href="{{ route('home') }}" class="hover:text-indigo-600 transition-colors">Home</a>
                <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                <a href="{{ route('finance.dashboard') }}" class="hover:text-indigo-600 transition-colors">Finance</a>
                <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                <a href="{{ route('finance.invoices.index') }}"
                   class="hover:text-indigo-600 transition-colors">Invoices</a>
                <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                <span class="text-gray-900">Details</span>
            </nav>
        </div>
        <div class="flex items-center gap-3">
            @if($invoice->status != 'cancelled' && $invoice->status != 'paid')
                <button @click="$dispatch('open-modal', 'apply-discount')"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 transition-colors">
                    <i data-lucide="tag" class="w-4 h-4"></i> Apply Discount
                </button>
            @endif

            <a href="{{ route('finance.invoices.print', $invoice->id) }}" target="_blank"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 transition-colors">
                <i data-lucide="printer" class="w-4 h-4"></i> Print
            </a>

            @if($invoice->status != 'cancelled' && $invoice->status == 'pending')
                <form action="{{ route('finance.invoices.cancel', $invoice->id) }}" method="POST"
                      onsubmit="return confirm('Are you sure you want to cancel this invoice?')">
                    @csrf
                    <button type="submit"
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold text-white bg-red-600 hover:bg-red-700 transition-colors shadow-sm">
                        <i data-lucide="x-circle" class="w-4 h-4"></i> Cancel Invoice
                    </button>
                </form>
            @endif
        </div>
    </div>

    @include('session-messages')

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-card shadow-card border border-gray-200 p-6">
                <h3 class="font-heading text-sm font-semibold text-gray-700 uppercase tracking-wider mb-4 pb-2 border-b border-gray-100">
                    Student Information</h3>
                <div class="flex items-center gap-4 mb-4">
                    <div
                        class="w-12 h-12 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold">
                        {{ strtoupper(substr($invoice->student->first_name, 0, 1)) }}
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-900">{{ $invoice->student->first_name }} {{ $invoice->student->last_name }}</p>
                        <p class="text-xs text-gray-500">ID: {{ $invoice->student_id }}</p>
                    </div>
                </div>
                <a href="{{ route('student.profile.show', $invoice->student_id) }}"
                   class="text-xs text-indigo-600 font-semibold hover:underline">View Student Profile</a>
            </div>

            <div class="bg-white rounded-card shadow-card border border-gray-200 p-6">
                <h3 class="font-heading text-sm font-semibold text-gray-700 uppercase tracking-wider mb-4 pb-2 border-b border-gray-100">
                    Invoice Details</h3>
                <div class="space-y-4">
                    <div class="flex justify-between">
                        <p class="text-xs text-gray-500 uppercase font-semibold">Status</p>
                        @php
                            $statusColors = [
                                'draft' => 'bg-gray-100 text-gray-800',
                                'pending' => 'bg-amber-100 text-amber-800',
                                'partially_paid' => 'bg-blue-100 text-blue-800',
                                'paid' => 'bg-emerald-100 text-emerald-800',
                                'overdue' => 'bg-red-100 text-red-800',
                                'cancelled' => 'bg-gray-100 text-gray-500',
                            ];
                            $color = $statusColors[$invoice->status] ?? 'bg-gray-100 text-gray-800';
                        @endphp
                        <span
                            class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $color }}">
                        {{ ucfirst(str_replace('_', ' ', $invoice->status)) }}
                    </span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Issue Date</span>
                        <span class="font-medium text-gray-900">{{ $invoice->issue_date->format('M d, Y') }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Due Date</span>
                        <span
                            class="font-medium {{ $invoice->status == 'overdue' ? 'text-red-600' : 'text-gray-900' }}">{{ $invoice->due_date->format('M d, Y') }}</span>
                    </div>
                    <div class="pt-4 border-t border-gray-100 space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Total Amount</span>
                            <span class="font-bold text-gray-900">@money($invoice->total)</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Paid Amount</span>
                            <span
                                class="font-bold text-emerald-600">@money($invoice->paid_amount)</span>
                        </div>
                        <div class="flex justify-between text-base">
                            <span class="text-gray-900 font-bold">Balance Due</span>
                            <span class="font-black text-red-600">@money($invoice->balance)</span>
                        </div>
                    </div>
                </div>

                @if($invoice->balance > 0 && $invoice->status != 'cancelled')
                    <div class="mt-6">
                        <a href="{{ route('finance.payments.create', ['student_id' => $invoice->student_id]) }}"
                           class="block w-full text-center bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 rounded-lg transition-colors">
                            Record Payment
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-card shadow-card border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-heading text-sm font-semibold text-gray-700 uppercase tracking-wider">Invoice
                        Items</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead>
                        <tr class="bg-gray-50 border-b border-gray-200">
                            <th class="px-6 py-3 font-semibold text-gray-700">Fee Type</th>
                            <th class="px-6 py-3 font-semibold text-gray-700 text-right">Amount</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                        @foreach($invoice->items as $item)
                            <tr>
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $item->feeType->name }}</td>
                                <td class="px-6 py-4 text-right text-gray-900 font-semibold">
                                    @money($item->amount)</td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50 divide-y divide-gray-200">
                        @foreach($invoice->discounts as $discount)
                            <tr>
                                <td class="px-6 py-3 text-emerald-600 font-medium text-right italic">
                                    Discount: {{ $discount->name }}
                                    <form action="{{ route('finance.invoices.remove-discount', $invoice->id) }}"
                                          method="POST" class="inline ml-2">
                                        @csrf
                                        <input type="hidden" name="discount_id" value="{{ $discount->id }}">
                                        <button type="submit" class="text-red-400 hover:text-red-600"><i
                                                data-lucide="trash-2" class="w-3 h-3"></i></button>
                                    </form>
                                </td>
                                <td class="px-6 py-3 text-right text-emerald-600 font-bold">
                                    -@money($discount->pivot->amount)</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td class="px-6 py-4 font-bold text-gray-900 text-right uppercase tracking-wider">Total</td>
                            <td class="px-6 py-4 text-right font-black text-indigo-600 text-lg">
                                @money($invoice->total)</td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="bg-white rounded-card shadow-card border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-heading text-sm font-semibold text-gray-700 uppercase tracking-wider">Payment
                        History</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead>
                        <tr class="bg-gray-50 border-b border-gray-200">
                            <th class="px-6 py-3 font-semibold text-gray-700">Receipt #</th>
                            <th class="px-6 py-3 font-semibold text-gray-700">Date</th>
                            <th class="px-6 py-3 font-semibold text-gray-700">Method</th>
                            <th class="px-6 py-3 font-semibold text-gray-700 text-right">Amount Paid</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                        @php $hasPayments = false; @endphp
                        @foreach($invoice->payments as $payment)
                            @php $hasPayments = true; @endphp
                            <tr>
                                <td class="px-6 py-4">
                                    <a href="{{ route('finance.payments.show', $payment->id) }}"
                                       class="text-indigo-600 font-medium hover:underline">{{ $payment->receipt_number }}</a>
                                </td>
                                <td class="px-6 py-4 text-gray-600">{{ $payment->payment_date->format('M d, Y') }}</td>
                                <td class="px-6 py-4 text-gray-600 capitalize">{{ str_replace('_', ' ', $payment->method) }}</td>
                                <td class="px-6 py-4 text-right text-emerald-600 font-bold">
                                    @money($payment->pivot->amount)</td>
                            </tr>
                        @endforeach

                        @if(!$hasPayments)
                            <tr>
                                <td colspan="4" class="px-6 py-10 text-center text-gray-500 italic">No payments recorded
                                    for this invoice yet.
                                </td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Apply Discount Modal --}}
    @push('modals')
        <div x-data="{}"
             x-show="activeModal === 'apply-discount'"
             class="fixed inset-0 z-50 overflow-y-auto"
             style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="closeModal()"></div>
                <div
                    class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full">
                    <form action="{{ route('finance.invoices.apply-discount', $invoice->id) }}" method="POST">
                        @csrf
                        <div class="px-6 py-4 border-b border-gray-100">
                            <h3 class="text-lg font-bold text-gray-900">Apply Discount</h3>
                        </div>
                        <div class="p-6">
                            <label for="discount_id" class="block text-sm font-medium text-gray-700 mb-2">Select
                                Discount</label>
                            <select name="discount_id" id="discount_id" required
                                    class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                                <option value="">Choose a discount...</option>
                                @foreach($discounts as $discount)
                                    <option value="{{ $discount->id }}">{{ $discount->name }}
                                        (@if($discount->type == 'percentage')
                                            {{ $discount->value }}%
                                        @else
                                            @money($discount->value)
                                        @endif)
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="px-6 py-4 bg-gray-50 flex justify-end gap-3">
                            <button type="button" @click="closeModal()"
                                    class="px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg">
                                Cancel
                            </button>
                            <button type="submit"
                                    class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow-sm">
                                Apply Discount
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endpush

@endsection
