@extends('layouts.app')

@section('page-title', 'Payment Details')

@section('content')

    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="font-heading text-xl font-bold text-gray-900">Payment: {{ $payment->receipt_number }}</h1>
            <nav class="flex items-center gap-1.5 mt-1 text-sm text-gray-500">
                <a href="{{ route('home') }}" class="hover:text-indigo-600 transition-colors">Home</a>
                <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                <a href="{{ route('finance.dashboard') }}" class="hover:text-indigo-600 transition-colors">Finance</a>
                <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                <a href="{{ route('finance.payments.index') }}"
                   class="hover:text-indigo-600 transition-colors">Payments</a>
                <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                <span class="text-gray-900">Details</span>
            </nav>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('finance.payments.receipt', $payment->id) }}" target="_blank"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 transition-colors shadow-sm">
                <i data-lucide="printer" class="w-4 h-4"></i> Print Receipt
            </a>
        </div>
    </div>

    @include('session-messages')

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-card shadow-card border border-gray-200 p-6">
                <h3 class="font-heading text-sm font-semibold text-gray-700 uppercase tracking-wider mb-4 pb-2 border-b border-gray-100">
                    Payment Info</h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-semibold">Student</p>
                        <p class="text-sm font-bold text-gray-900">{{ $payment->student->first_name }} {{ $payment->student->last_name }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-semibold">Amount</p>
                        <p class="text-lg font-black text-emerald-600">${{ number_format($payment->amount, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-semibold">Date</p>
                        <p class="text-sm font-medium text-gray-900">{{ $payment->payment_date->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-semibold">Method</p>
                        <p class="text-sm font-medium text-gray-900 capitalize">{{ str_replace('_', ' ', $payment->method) }}</p>
                    </div>
                    @if($payment->reference)
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-semibold">Reference</p>
                            <p class="text-sm font-medium text-gray-900">{{ $payment->reference }}</p>
                        </div>
                    @endif
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-semibold">Received By</p>
                        <p class="text-sm font-medium text-gray-900">{{ $payment->receiver->first_name ?? 'System' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2">
            <div class="bg-white rounded-card shadow-card border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-heading text-sm font-semibold text-gray-700 uppercase tracking-wider">
                        Allocations</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead>
                        <tr class="bg-gray-50 border-b border-gray-200">
                            <th class="px-6 py-3 font-semibold text-gray-700">Invoice #</th>
                            <th class="px-6 py-3 font-semibold text-gray-700 text-right">Allocated Amount</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                        @foreach($payment->invoices as $invoice)
                            <tr>
                                <td class="px-6 py-4 font-medium text-indigo-600 hover:underline">
                                    <a href="{{ route('finance.invoices.show', $invoice->id) }}">{{ $invoice->invoice_number }}</a>
                                </td>
                                <td class="px-6 py-4 text-right text-emerald-600 font-bold">
                                    ${{ number_format($invoice->pivot->amount, 2) }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr class="bg-gray-50">
                            <td class="px-6 py-4 font-bold text-gray-900 text-right">Total</td>
                            <td class="px-6 py-4 text-right font-black text-indigo-600 text-lg">
                                ${{ number_format($payment->amount, 2) }}</td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection
