@extends('layouts.app')

@section('page-title', 'Outstanding Fees Report')

@section('content')

    <div class="mb-6">
        <h1 class="font-heading text-xl font-bold text-gray-900">Outstanding Fees Report</h1>
        <nav class="flex items-center gap-1.5 mt-1 text-sm text-gray-500">
            <a href="{{ route('home') }}" class="hover:text-indigo-600 transition-colors">Home</a>
            <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
            <a href="{{ route('finance.dashboard') }}" class="hover:text-indigo-600 transition-colors">Finance</a>
            <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
            <a href="{{ route('finance.reports.index') }}" class="hover:text-indigo-600 transition-colors">Reports</a>
            <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
            <span class="text-gray-900">Outstanding Fees</span>
        </nav>
    </div>

    <div class="bg-white rounded-card shadow-card border border-gray-200 p-6 mb-8">
        <form action="{{ route('finance.reports.outstanding') }}" method="GET" class="flex flex-wrap items-end gap-4">
            <div>
                <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Aging Bucket</label>
                <select name="bucket"
                        class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm bg-white focus:outline-none focus:border-indigo-500">
                    <option value="">All Buckets</option>
                    @foreach($buckets_display as $key => $label)
                        <option value="{{ $key }}" {{ $bucket == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-6 py-2 rounded-lg transition-colors text-sm">
                Filter
            </button>
            <a href="{{ route('finance.reports.outstanding') }}"
               class="px-4 py-2 border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50 transition-colors text-sm">Clear</a>
        </form>
    </div>

    <div class="space-y-6">
        @foreach($buckets_display as $key => $label)
            @if(!$bucket || $bucket == $key)
                <div class="bg-white rounded-card shadow-card border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                        <h3 class="font-heading text-sm font-bold text-gray-900 uppercase tracking-wider">{{ $label }}</h3>
                        <span class="text-xs font-medium px-2 py-0.5 rounded-full bg-red-100 text-red-700">
                    Total: @money($aging[$key]->reduce(fn($carry, $item) => $carry->add($item->balance), \App\Helpers\MoneyHelper::zero()))
                </span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead>
                            <tr class="bg-white border-b border-gray-100 text-[11px] uppercase text-gray-500">
                                <th class="px-6 py-3 font-semibold">Student</th>
                                <th class="px-6 py-3 font-semibold">Invoice #</th>
                                <th class="px-6 py-3 font-semibold">Due Date</th>
                                <th class="px-6 py-3 font-semibold">Days Overdue</th>
                                <th class="px-6 py-3 font-semibold text-right">Balance</th>
                                <th class="px-6 py-3 font-semibold text-right">Actions</th>
                            </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                            @forelse($aging[$key] as $invoice)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div
                                            class="font-bold text-gray-900">{{ $invoice->student->first_name }} {{ $invoice->student->last_name }}</div>
                                        <div class="text-[10px] text-gray-400">ID: {{ $invoice->student_id }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-indigo-600 font-medium">
                                        <a href="{{ route('finance.invoices.show', $invoice->id) }}">{{ $invoice->invoice_number }}</a>
                                    </td>
                                    <td class="px-6 py-4 text-gray-600 whitespace-nowrap">{{ $invoice->due_date->format('M d, Y') }}</td>
                                    <td class="px-6 py-4">
                                        <span class="font-bold text-red-600">{{ now()->diffInDays($invoice->due_date) }} days</span>
                                    </td>
                                    <td class="px-6 py-4 text-right font-black text-gray-900">
                                        @money($invoice->balance)</td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('finance.payments.create', ['student_id' => $invoice->student_id]) }}"
                                           class="text-xs font-bold text-indigo-600 hover:underline uppercase">Collect</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-gray-400 italic">No outstanding
                                        invoices in this bucket.
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        @endforeach
    </div>

@endsection
