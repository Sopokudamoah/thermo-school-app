@extends('layouts.app')

@section('page-title', 'Financial Reports')

@section('content')

    <div class="mb-6">
        <h1 class="font-heading text-xl font-bold text-gray-900">Financial Reports</h1>
        <nav class="flex items-center gap-1.5 mt-1 text-sm text-gray-500">
            <a href="{{ route('home') }}" class="hover:text-indigo-600 transition-colors">Home</a>
            <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
            <a href="{{ route('finance.dashboard') }}" class="hover:text-indigo-600 transition-colors">Finance</a>
            <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
            <span class="text-gray-900">Reports</span>
        </nav>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        {{-- Fee Collection Report --}}
        <a href="{{ route('finance.reports.fee-collection') }}"
           class="bg-white rounded-card shadow-card border border-gray-200 p-6 hover:border-indigo-300 hover:shadow-md transition-all group">
            <div
                class="w-12 h-12 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 mb-4 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                <i data-lucide="receipt" class="w-6 h-6"></i>
            </div>
            <h3 class="font-heading text-lg font-bold text-gray-900 mb-2">Fee Collection</h3>
            <p class="text-sm text-gray-500">Detailed list of all fees collected within a specific date range, class or
                section.</p>
        </a>

        {{-- Outstanding Fees Report --}}
        <a href="{{ route('finance.reports.outstanding') }}"
           class="bg-white rounded-card shadow-card border border-gray-200 p-6 hover:border-indigo-300 hover:shadow-md transition-all group">
            <div
                class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center text-red-600 mb-4 group-hover:bg-red-600 group-hover:text-white transition-colors">
                <i data-lucide="clock" class="w-6 h-6"></i>
            </div>
            <h3 class="font-heading text-lg font-bold text-gray-900 mb-2">Outstanding Fees</h3>
            <p class="text-sm text-gray-500">Aging report showing students with unpaid invoices grouped by time buckets
                (30, 60, 90+ days).</p>
        </a>

        {{-- Revenue Report --}}
        <a href="{{ route('finance.reports.revenue') }}"
           class="bg-white rounded-card shadow-card border border-gray-200 p-6 hover:border-indigo-300 hover:shadow-md transition-all group">
            <div
                class="w-12 h-12 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 mb-4 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                <i data-lucide="bar-chart-3" class="w-6 h-6"></i>
            </div>
            <h3 class="font-heading text-lg font-bold text-gray-900 mb-2">Revenue Report</h3>
            <p class="text-sm text-gray-500">Summary of total billed revenue grouped by fee types and categories.</p>
        </a>

        {{-- Expense Report --}}
        <a href="{{ route('finance.reports.expense') }}"
           class="bg-white rounded-card shadow-card border border-gray-200 p-6 hover:border-indigo-300 hover:shadow-md transition-all group">
            <div
                class="w-12 h-12 rounded-full bg-amber-100 flex items-center justify-center text-amber-600 mb-4 group-hover:bg-amber-600 group-hover:text-white transition-colors">
                <i data-lucide="wallet" class="w-6 h-6"></i>
            </div>
            <h3 class="font-heading text-lg font-bold text-gray-900 mb-2">Expense Report</h3>
            <p class="text-sm text-gray-500">Detailed breakdown of school expenditures by category and status.</p>
        </a>

        {{-- Student Ledger --}}
        <a href="{{ route('finance.reports.student-ledger') }}"
           class="bg-white rounded-card shadow-card border border-gray-200 p-6 hover:border-indigo-300 hover:shadow-md transition-all group">
            <div
                class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 mb-4 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                <i data-lucide="book-open" class="w-6 h-6"></i>
            </div>
            <h3 class="font-heading text-lg font-bold text-gray-900 mb-2">Student Ledger</h3>
            <p class="text-sm text-gray-500">Comprehensive statement of account for an individual student showing all
                invoices and payments.</p>
        </a>

        {{-- Audit Trail --}}
        <a href="{{ route('finance.reports.audit-trail') }}"
           class="bg-white rounded-card shadow-card border border-gray-200 p-6 hover:border-indigo-300 hover:shadow-md transition-all group">
            <div
                class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center text-gray-600 mb-4 group-hover:bg-gray-600 group-hover:text-white transition-colors">
                <i data-lucide="history" class="w-6 h-6"></i>
            </div>
            <h3 class="font-heading text-lg font-bold text-gray-900 mb-2">Audit Trail</h3>
            <p class="text-sm text-gray-500">Immutable logs of all financial transactions and modifications for security
                and accountability.</p>
        </a>
    </div>

@endsection
