@extends('layouts.app')

@section('page-title', 'Fee Collection Report')

@section('content')

    <div class="mb-6">
        <h1 class="font-heading text-xl font-bold text-gray-900">Fee Collection Report</h1>
        <nav class="flex items-center gap-1.5 mt-1 text-sm text-gray-500">
            <a href="{{ route('home') }}" class="hover:text-indigo-600 transition-colors">Home</a>
            <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
            <a href="{{ route('finance.dashboard') }}" class="hover:text-indigo-600 transition-colors">Finance</a>
            <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
            <a href="{{ route('finance.reports.index') }}" class="hover:text-indigo-600 transition-colors">Reports</a>
            <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
            <span class="text-gray-900">Fee Collection</span>
        </nav>
    </div>

    <div class="bg-white rounded-card shadow-card border border-gray-200 p-6 mb-8">
        <form action="{{ route('finance.reports.fee-collection') }}" method="GET"
              class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <div>
                <label class="block text-xs font-medium text-gray-500 uppercase mb-1">From Date</label>
                <input type="date" name="from_date" value="{{ $filters['from_date'] ?? '' }}"
                       class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-indigo-500">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 uppercase mb-1">To Date</label>
                <input type="date" name="to_date" value="{{ $filters['to_date'] ?? '' }}"
                       class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-indigo-500">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Class</label>
                <select name="class_id"
                        class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm bg-white focus:outline-none focus:border-indigo-500">
                    <option value="">All Classes</option>
                    @foreach($classes as $class)
                        <option
                            value="{{ $class->id }}" {{ ($filters['class_id'] ?? '') == $class->id ? 'selected' : '' }}>{{ $class->class_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit"
                        class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 rounded-lg transition-colors text-sm">
                    Generate Report
                </button>
                <a href="{{ route('finance.reports.fee-collection') }}"
                   class="px-4 py-2 border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50 transition-colors text-sm flex items-center">Reset</a>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-card shadow-card border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center text-sm">
            <span class="font-medium text-gray-700">Results: {{ $payments->count() }} payments found</span>
            <span class="font-black text-emerald-600 text-lg">Total Collected: @money($total)</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                <tr class="bg-white border-b border-gray-200 text-[11px] uppercase text-gray-500">
                    <th class="px-6 py-3 font-semibold">Date</th>
                    <th class="px-6 py-3 font-semibold">Receipt #</th>
                    <th class="px-6 py-3 font-semibold">Student</th>
                    <th class="px-6 py-3 font-semibold">Invoices</th>
                    <th class="px-6 py-3 font-semibold">Method</th>
                    <th class="px-6 py-3 font-semibold text-right">Amount</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                @forelse($payments as $payment)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4 text-gray-600 whitespace-nowrap">{{ $payment->payment_date->format('M d, Y') }}</td>
                        <td class="px-6 py-4 font-medium text-indigo-600">
                            <a href="{{ route('finance.payments.show', $payment->id) }}">{{ $payment->receipt_number }}</a>
                        </td>
                        <td class="px-6 py-4">
                            <div
                                class="font-bold text-gray-900">{{ $payment->student->first_name }} {{ $payment->student->last_name }}</div>
                            <div class="text-[10px] text-gray-400">ID: {{ $payment->student_id }}</div>
                        </td>
                        <td class="px-6 py-4">
                            @foreach($payment->allocations as $alc)
                                <span
                                    class="inline-block px-1.5 py-0.5 rounded bg-gray-100 text-[10px] font-medium text-gray-600 mb-1">#{{ $alc->invoice->invoice_number }}</span>
                            @endforeach
                        </td>
                        <td class="px-6 py-4 capitalize text-gray-600">{{ str_replace('_', ' ', $payment->method) }}</td>
                        <td class="px-6 py-4 text-right font-black text-emerald-600">
                            @money($payment->amount)</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-gray-400 italic">No data found for the
                            selected filters.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection
