@extends('layouts.app')

@section('page-title', 'Student Ledger')

@section('content')

    <div class="mb-6">
        <h1 class="font-heading text-xl font-bold text-gray-900">Student Ledger</h1>
        <nav class="flex items-center gap-1.5 mt-1 text-sm text-gray-500">
            <a href="{{ route('home') }}" class="hover:text-indigo-600 transition-colors">Home</a>
            <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
            <a href="{{ route('finance.dashboard') }}" class="hover:text-indigo-600 transition-colors">Finance</a>
            <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
            <a href="{{ route('finance.reports.index') }}" class="hover:text-indigo-600 transition-colors">Reports</a>
            <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
            <span class="text-gray-900">Student Ledger</span>
        </nav>
    </div>

    <div class="bg-white rounded-card shadow-card border border-gray-200 p-6 mb-8">
        <form action="{{ route('finance.reports.student-ledger') }}" method="GET"
              class="flex flex-wrap items-end gap-4">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Select Student</label>
                <select name="student_id" required
                        class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm bg-white focus:outline-none focus:border-indigo-500">
                    <option value="">Choose Student...</option>
                    @foreach($students as $s)
                        <option
                            value="{{ $s->id }}" {{ ($student->id ?? '') == $s->id ? 'selected' : '' }}>{{ $s->first_name }} {{ $s->last_name }}
                            (ID: {{ $s->id }})
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-6 py-2 rounded-lg transition-colors text-sm">
                View Ledger
            </button>
        </form>
    </div>

    @if($student)
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <div class="lg:col-span-1 bg-white rounded-card shadow-card border border-gray-200 p-6">
                <h3 class="font-heading text-sm font-semibold text-gray-700 uppercase tracking-wider mb-4 pb-2 border-b border-gray-100">
                    Student Profile</h3>
                <div class="flex items-center gap-4 mb-6">
                    <div
                        class="w-16 h-16 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-black text-xl">
                        {{ strtoupper(substr($student->first_name, 0, 1)) }}
                    </div>
                    <div>
                        <p class="text-lg font-bold text-gray-900">{{ $student->first_name }} {{ $student->last_name }}</p>
                        <p class="text-sm text-gray-500">ID: {{ $student->id }}</p>
                    </div>
                </div>
                <div class="space-y-3 pt-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Total Billed</span>
                        <span class="font-bold text-gray-900">${{ number_format($invoices->sum('total'), 2) }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Total Paid</span>
                        <span
                            class="font-bold text-emerald-600">${{ number_format($payments->sum('amount'), 2) }}</span>
                    </div>
                    <div class="flex justify-between text-lg font-black pt-2 border-t border-gray-100">
                        <span class="text-gray-900">Balance</span>
                        <span class="text-red-600">${{ number_format($invoices->sum('balance'), 2) }}</span>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-card shadow-card border border-gray-200 overflow-hidden">
                    <div
                        class="px-6 py-4 border-b border-gray-200 bg-gray-50 font-bold text-gray-700 uppercase tracking-wider text-sm">
                        Statement of Account
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead>
                            <tr class="bg-white border-b border-gray-100 text-[11px] uppercase text-gray-500">
                                <th class="px-6 py-3 font-semibold text-gray-600">Date</th>
                                <th class="px-6 py-3 font-semibold text-gray-600">Ref / Description</th>
                                <th class="px-6 py-3 font-semibold text-gray-600 text-right">Debit (+)</th>
                                <th class="px-6 py-3 font-semibold text-gray-600 text-right">Credit (-)</th>
                                <th class="px-6 py-3 font-semibold text-gray-600 text-right">Balance</th>
                            </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                            @php
                                $running_balance = 0;
                                $ledger = collect();
                                foreach($invoices as $inv) {
                                    $ledger->push([
                                        'date' => $inv->issue_date,
                                        'ref' => 'Invoice #' . $inv->invoice_number,
                                        'debit' => $inv->total,
                                        'credit' => 0,
                                        'link' => route('finance.invoices.show', $inv->id)
                                    ]);
                                }
                                foreach($payments as $pay) {
                                    $ledger->push([
                                        'date' => $pay->payment_date,
                                        'ref' => 'Payment #' . $pay->receipt_number,
                                        'debit' => 0,
                                        'credit' => $pay->amount,
                                        'link' => route('finance.payments.show', $pay->id)
                                    ]);
                                }
                                $ledger = $ledger->sortBy('date');
                            @endphp

                            @forelse($ledger as $row)
                                @php $running_balance += ($row['debit'] - $row['credit']); @endphp
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-6 py-4 text-gray-600 whitespace-nowrap">{{ $row['date']->format('M d, Y') }}</td>
                                    <td class="px-6 py-4">
                                        <a href="{{ $row['link'] }}"
                                           class="text-indigo-600 font-medium hover:underline">{{ $row['ref'] }}</a>
                                    </td>
                                    <td class="px-6 py-4 text-right text-gray-900">{{ $row['debit'] > 0 ? '$' . number_format($row['debit'], 2) : '-' }}</td>
                                    <td class="px-6 py-4 text-right text-emerald-600">{{ $row['credit'] > 0 ? '-$' . number_format($row['credit'], 2) : '-' }}</td>
                                    <td class="px-6 py-4 text-right font-bold text-gray-900">
                                        ${{ number_format($running_balance, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-10 text-center text-gray-400 italic">No transactions
                                        found for this student.
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif

@endsection
