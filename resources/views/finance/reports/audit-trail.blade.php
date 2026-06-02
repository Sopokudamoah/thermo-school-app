@extends('layouts.app')

@section('page-title', 'Audit Trail')

@section('content')

    <div class="mb-6">
        <h1 class="font-heading text-xl font-bold text-gray-900">Financial Audit Trail</h1>
        <nav class="flex items-center gap-1.5 mt-1 text-sm text-gray-500">
            <a href="{{ route('home') }}" class="hover:text-indigo-600 transition-colors">Home</a>
            <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
            <a href="{{ route('finance.dashboard') }}" class="hover:text-indigo-600 transition-colors">Finance</a>
            <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
            <a href="{{ route('finance.reports.index') }}" class="hover:text-indigo-600 transition-colors">Reports</a>
            <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
            <span class="text-gray-900">Audit Trail</span>
        </nav>
    </div>

    <div class="bg-white rounded-card shadow-card border border-gray-200 p-6 mb-8">
        <form action="{{ route('finance.reports.audit-trail') }}" method="GET"
              class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <div>
                <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Action</label>
                <select name="action"
                        class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm bg-white focus:outline-none focus:border-indigo-500">
                    <option value="">All Actions</option>
                    <option
                        value="invoice_created" {{ ($filters['action'] ?? '') == 'invoice_created' ? 'selected' : '' }}>
                        Invoice Created
                    </option>
                    <option
                        value="payment_posted" {{ ($filters['action'] ?? '') == 'payment_posted' ? 'selected' : '' }}>
                        Payment Posted
                    </option>
                    <option
                        value="expense_approved" {{ ($filters['action'] ?? '') == 'expense_approved' ? 'selected' : '' }}>
                        Expense Approved
                    </option>
                    <option
                        value="scholarship_approved" {{ ($filters['action'] ?? '') == 'scholarship_approved' ? 'selected' : '' }}>
                        Scholarship Approved
                    </option>
                </select>
            </div>
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
            <div class="flex gap-2">
                <button type="submit"
                        class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 rounded-lg transition-colors text-sm">
                    Filter
                </button>
                <a href="{{ route('finance.reports.audit-trail') }}"
                   class="px-4 py-2 border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50 transition-colors text-sm flex items-center">Reset</a>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-card shadow-card border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                <tr class="bg-gray-50 border-b border-gray-200 text-[11px] uppercase text-gray-500">
                    <th class="px-6 py-3 font-semibold">Timestamp</th>
                    <th class="px-6 py-3 font-semibold">User</th>
                    <th class="px-6 py-3 font-semibold">Action</th>
                    <th class="px-6 py-3 font-semibold">Details</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                @forelse($logs as $log)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4 text-gray-600 whitespace-nowrap text-xs">{{ $log->created_at->format('M d, Y H:i:s') }}</td>
                        <td class="px-6 py-4 font-medium text-gray-900">{{ $log->user->first_name }} {{ $log->user->last_name }}</td>
                        <td class="px-6 py-4">
                        <span
                            class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 text-gray-700 uppercase tracking-tight">
                            {{ str_replace('_', ' ', $log->action) }}
                        </span>
                        </td>
                        <td class="px-6 py-4 text-xs text-gray-500 max-w-md truncate">
                            @if($log->new_values)
                                {{ json_encode($log->new_values) }}
                            @else
                                N/A
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-10 text-center text-gray-400 italic">No audit logs found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $logs->links() }}
        </div>
    </div>

@endsection
