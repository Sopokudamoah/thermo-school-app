@extends('layouts.app')

@section('page-title', 'Expense Report')

@section('content')

    <div class="mb-6">
        <h1 class="font-heading text-xl font-bold text-gray-900">Expense Report</h1>
        <nav class="flex items-center gap-1.5 mt-1 text-sm text-gray-500">
            <a href="{{ route('home') }}" class="hover:text-indigo-600 transition-colors">Home</a>
            <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
            <a href="{{ route('finance.dashboard') }}" class="hover:text-indigo-600 transition-colors">Finance</a>
            <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
            <a href="{{ route('finance.reports.index') }}" class="hover:text-indigo-600 transition-colors">Reports</a>
            <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
            <span class="text-gray-900">Expenses</span>
        </nav>
    </div>

    <div class="bg-white rounded-card shadow-card border border-gray-200 p-6 mb-8">
        <form action="{{ route('finance.reports.expense') }}" method="GET"
              class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
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
                    Generate
                </button>
                <a href="{{ route('finance.reports.expense') }}"
                   class="px-4 py-2 border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50 transition-colors text-sm flex items-center">Reset</a>
            </div>
        </form>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        {{-- Expense breakdown by category --}}
        <div class="bg-white rounded-card shadow-card border border-gray-200 overflow-hidden">
            <div
                class="px-6 py-4 border-b border-gray-200 bg-gray-50 font-bold text-gray-700 uppercase tracking-wider text-sm">
                Breakdown by Category
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                    <tr class="bg-white border-b border-gray-100 text-[11px] uppercase text-gray-500">
                        <th class="px-6 py-3 font-semibold text-gray-600">Category</th>
                        <th class="px-6 py-3 font-semibold text-gray-600 text-right">Transactions</th>
                        <th class="px-6 py-3 font-semibold text-gray-600 text-right">Total Amount</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                    @php $grand_total = 0; @endphp
                    @forelse($expense_data as $row)
                        @php $grand_total += $row->total; @endphp
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $row->category->name }}</td>
                            <td class="px-6 py-4 text-right text-gray-600">{{ $row->count }}</td>
                            <td class="px-6 py-4 text-right font-black text-gray-900">
                                @money($row->total)</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-10 text-center text-gray-400 italic">No approved expenses
                                found for the selected period.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                    @if($grand_total > 0)
                        <tfoot class="bg-gray-50">
                        <tr>
                            <td colspan="2" class="px-6 py-4 font-bold text-gray-900 uppercase">Grand Total</td>
                            <td class="px-6 py-4 text-right font-black text-red-600 text-lg">
                                @money($grand_total)</td>
                        </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        </div>

        {{-- Expense categories stats --}}
        <div class="bg-white rounded-card shadow-card border border-gray-200 p-6">
            <h3 class="font-heading text-sm font-semibold text-gray-700 uppercase tracking-wider mb-6 pb-2 border-b border-gray-100 text-center">
                Category Comparison</h3>
            <div class="space-y-4">
                @foreach($expense_data as $row)
                    @php $percent = $grand_total > 0 ? ($row->total / $grand_total) * 100 : 0; @endphp
                    <div>
                        <div class="flex justify-between text-xs mb-1">
                            <span class="font-bold text-gray-700">{{ $row->category->name }}</span>
                            <span
                                class="text-gray-500">@money($row->total) ({{ round($percent) }}%)</span>
                        </div>
                        <div class="w-full h-2 bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-full bg-amber-500" style="width: {{ $percent }}%"></div>
                        </div>
                    </div>
                @endforeach

                @if($expense_data->isEmpty())
                    <div class="py-20 text-center text-gray-400 italic">
                        No data to compare.
                    </div>
                @endif
            </div>
        </div>
    </div>

@endsection
