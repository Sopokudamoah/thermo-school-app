@extends('layouts.app')

@section('page-title', 'Finance Dashboard')

@push('head-scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@section('content')

    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="font-heading text-xl font-bold text-gray-900">Finance Dashboard</h1>
            <nav class="flex items-center gap-1.5 mt-1 text-sm text-gray-500">
                <a href="{{ route('home') }}" class="hover:text-indigo-600 transition-colors">Home</a>
                <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                <span class="text-gray-900">Finance</span>
            </nav>
        </div>
        <div class="flex items-center gap-3 text-sm text-gray-500 font-medium">
            <i data-lucide="calendar" class="w-4 h-4 text-indigo-600"></i>
            Fiscal Year {{ date('Y') }}
        </div>
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
        <div class="bg-white rounded-card shadow-card border border-gray-200 p-5">
            <p class="text-xs text-gray-500 font-semibold uppercase mb-1">Fees Billed</p>
            <p class="text-2xl font-black text-gray-900">${{ number_format($fees_billed, 2) }}</p>
        </div>
        <div class="bg-white rounded-card shadow-card border border-gray-200 p-5">
            <p class="text-xs text-gray-500 font-semibold uppercase mb-1">Fees Collected</p>
            <p class="text-2xl font-black text-emerald-600">${{ number_format($fees_collected, 2) }}</p>
        </div>
        <div class="bg-white rounded-card shadow-card border border-gray-200 p-5">
            <p class="text-xs text-gray-500 font-semibold uppercase mb-1">Outstanding</p>
            <p class="text-2xl font-black text-red-600">${{ number_format($outstanding, 2) }}</p>
        </div>
        <div class="bg-white rounded-card shadow-card border border-gray-200 p-5">
            <p class="text-xs text-gray-500 font-semibold uppercase mb-1">Expenses</p>
            <p class="text-2xl font-black text-amber-600">${{ number_format($expenses, 2) }}</p>
        </div>
        <div class="bg-white rounded-card shadow-card border border-gray-200 p-5">
            <p class="text-xs text-gray-500 font-semibold uppercase mb-1">Net Position</p>
            <p class="text-2xl font-black {{ $net_position >= 0 ? 'text-indigo-600' : 'text-red-600' }}">
                ${{ number_format($net_position, 2) }}
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        {{-- Collection vs Expense Trend --}}
        <div class="bg-white rounded-card shadow-card border border-gray-200 p-6">
            <h3 class="font-heading text-sm font-semibold text-gray-700 uppercase tracking-wider mb-6 pb-2 border-b border-gray-100">
                Monthly Trend ({{ date('Y') }})</h3>
            <div class="h-64">
                <canvas id="trendChart"></canvas>
            </div>
        </div>

        {{-- Revenue by Fee Type --}}
        <div class="bg-white rounded-card shadow-card border border-gray-200 p-6">
            <h3 class="font-heading text-sm font-semibold text-gray-700 uppercase tracking-wider mb-6 pb-2 border-b border-gray-100">
                Revenue by Fee Type</h3>
            <div class="h-64">
                <canvas id="revenuePieChart"></canvas>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Quick Actions --}}
        <div class="lg:col-span-1 bg-white rounded-card shadow-card border border-gray-200 p-6">
            <h3 class="font-heading text-sm font-semibold text-gray-700 uppercase tracking-wider mb-4 pb-2 border-b border-gray-100">
                Quick Actions</h3>
            <div class="grid grid-cols-1 gap-3">
                @can('finance.invoice.create')
                    <a href="{{ route('finance.invoices.create') }}"
                       class="flex items-center gap-3 p-3 rounded-lg border border-gray-100 hover:bg-indigo-50 hover:border-indigo-100 transition-colors group">
                        <div
                            class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600">
                            <i data-lucide="file-plus" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-gray-900 group-hover:text-indigo-700">Create Invoice</p>
                            <p class="text-[11px] text-gray-500">Bill fees to a student</p>
                        </div>
                    </a>
                @endcan

                @can('finance.payment.create')
                    <a href="{{ route('finance.payments.create') }}"
                       class="flex items-center gap-3 p-3 rounded-lg border border-gray-100 hover:bg-emerald-50 hover:border-emerald-100 transition-colors group">
                        <div
                            class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600">
                            <i data-lucide="credit-card" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-gray-900 group-hover:text-emerald-700">Record Payment</p>
                            <p class="text-[11px] text-gray-500">Post a student payment</p>
                        </div>
                    </a>
                @endcan

                @can('finance.expense.create')
                    <a href="{{ route('finance.expenses.create') }}"
                       class="flex items-center gap-3 p-3 rounded-lg border border-gray-100 hover:bg-amber-50 hover:border-amber-100 transition-colors group">
                        <div
                            class="w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center text-amber-600">
                            <i data-lucide="minus-circle" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-gray-900 group-hover:text-amber-700">Submit Expense</p>
                            <p class="text-[11px] text-gray-500">Request expense approval</p>
                        </div>
                    </a>
                @endcan

                <a href="{{ route('finance.reports.index') }}"
                   class="flex items-center gap-3 p-3 rounded-lg border border-gray-100 hover:bg-gray-50 transition-colors group">
                    <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-600">
                        <i data-lucide="bar-chart-3" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-900 group-hover:text-indigo-600">Financial Reports</p>
                        <p class="text-[11px] text-gray-500">View detailed analytics</p>
                    </div>
                </a>
            </div>
        </div>

        {{-- Fee Types List --}}
        <div class="lg:col-span-2 bg-white rounded-card shadow-card border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-heading text-sm font-semibold text-gray-700 uppercase tracking-wider">Revenue
                    Breakdown</h3>
                <a href="{{ route('finance.fee-types.index') }}"
                   class="text-xs font-bold text-indigo-600 hover:underline uppercase">View All</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                    <tr class="bg-gray-50 border-b border-gray-200 text-[11px] uppercase text-gray-500">
                        <th class="px-6 py-3 font-semibold">Fee Type</th>
                        <th class="px-6 py-3 font-semibold text-right">Total Billed</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                    @forelse($revenue_by_fee_type as $rev)
                        <tr>
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $rev->name }}</td>
                            <td class="px-6 py-4 text-right font-bold text-gray-900">
                                ${{ number_format($rev->total, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="px-6 py-10 text-center text-gray-400 italic">No revenue data
                                available.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Trend Chart
                const trendCtx = document.getElementById('trendChart').getContext('2d');
                new Chart(trendCtx, {
                    type: 'line',
                    data: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                        datasets: [
                            {
                                label: 'Collection',
                                data: {{ json_encode($collection_trend) }},
                                borderColor: '#10b981',
                                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                                tension: 0.3,
                                fill: true
                            },
                            {
                                label: 'Expenses',
                                data: {{ json_encode($expense_trend) }},
                                borderColor: '#f59e0b',
                                backgroundColor: 'rgba(245, 158, 11, 0.1)',
                                tension: 0.3,
                                fill: true
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {position: 'bottom'}
                        },
                        scales: {
                            y: {beginAtZero: true, grid: {display: false}},
                            x: {grid: {display: false}}
                        }
                    }
                });

                // Revenue Pie Chart
                const pieCtx = document.getElementById('revenuePieChart').getContext('2d');
                new Chart(pieCtx, {
                    type: 'doughnut',
                    data: {
                        labels: {{ json_encode($revenue_by_fee_type->pluck('name')) }},
                        datasets: [{
                            data: {{ json_encode($revenue_by_fee_type->pluck('total')) }},
                            backgroundColor: [
                                '#4f46e5', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899', '#06b6d4'
                            ]
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {position: 'right'}
                        }
                    }
                });
            });
        </script>
    @endpush

@endsection
