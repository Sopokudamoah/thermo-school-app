@extends('layouts.app')

@section('page-title', 'Revenue Report')

@section('content')

    <div class="mb-6">
        <h1 class="font-heading text-xl font-bold text-gray-900">Revenue Report</h1>
        <nav class="flex items-center gap-1.5 mt-1 text-sm text-gray-500">
            <a href="{{ route('home') }}" class="hover:text-indigo-600 transition-colors">Home</a>
            <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
            <a href="{{ route('finance.dashboard') }}" class="hover:text-indigo-600 transition-colors">Finance</a>
            <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
            <a href="{{ route('finance.reports.index') }}" class="hover:text-indigo-600 transition-colors">Reports</a>
            <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
            <span class="text-gray-900">Revenue</span>
        </nav>
    </div>

    <div class="bg-white rounded-card shadow-card border border-gray-200 p-6 mb-8">
        <form action="{{ route('finance.reports.revenue') }}" method="GET"
              class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
            <div>
                <label class="block text-xs font-medium text-gray-500 uppercase mb-1">From Date</label>
                <input type="date" name="from_date" value="{{ $from }}"
                       class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-indigo-500">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 uppercase mb-1">To Date</label>
                <input type="date" name="to_date" value="{{ $to }}"
                       class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-indigo-500">
            </div>
            <div class="flex gap-2">
                <button type="submit"
                        class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 rounded-lg transition-colors text-sm">
                    Generate
                </button>
                <a href="{{ route('finance.reports.revenue') }}"
                   class="px-4 py-2 border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50 transition-colors text-sm flex items-center">Reset</a>
            </div>
        </form>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2">
            <div class="bg-white rounded-card shadow-card border border-gray-200 overflow-hidden">
                <div
                    class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center text-sm font-bold uppercase tracking-wider text-gray-700">
                    Revenue Breakdown by Fee Type
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead>
                        <tr class="bg-white border-b border-gray-100 text-[11px] uppercase text-gray-500">
                            <th class="px-6 py-3 font-semibold text-gray-600">Fee Type</th>
                            <th class="px-6 py-3 font-semibold text-gray-600 text-right">Total Revenue</th>
                            <th class="px-6 py-3 font-semibold text-gray-600 text-right w-48">Share</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                        @php $total_revenue = $revenue->sum('total'); @endphp
                        @forelse($revenue as $row)
                            @php $share = $total_revenue > 0 ? ($row->total / $total_revenue) * 100 : 0; @endphp
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $row->fee_type }}</td>
                                <td class="px-6 py-4 text-right font-black text-gray-900">
                                    @money($row->total)</td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center gap-2">
                                        <div class="flex-1 h-2 bg-gray-100 rounded-full overflow-hidden">
                                            <div class="h-full bg-indigo-500" style="width: {{ $share }}%"></div>
                                        </div>
                                        <span
                                            class="text-[10px] font-bold text-gray-500 w-8">{{ round($share) }}%</span>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-10 text-center text-gray-400 italic">No revenue data for
                                    the selected period.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                        @if($total_revenue > 0)
                            <tfoot class="bg-gray-50">
                            <tr>
                                <td class="px-6 py-4 font-bold text-gray-900 uppercase">Grand Total</td>
                                <td class="px-6 py-4 text-right font-black text-indigo-600 text-lg">
                                    @money($total_revenue)</td>
                                <td></td>
                            </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>
            </div>
        </div>

        <div class="lg:col-span-1">
            <div class="bg-white rounded-card shadow-card border border-gray-200 p-6 sticky top-24">
                <h3 class="font-heading text-sm font-semibold text-gray-700 uppercase tracking-wider mb-6 pb-2 border-b border-gray-100 text-center">
                    Summary</h3>
                <div class="space-y-4">
                    <div class="text-center">
                        <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Period Revenue</p>
                        <p class="text-3xl font-black text-indigo-600">@money($total_revenue)</p>
                    </div>
                    <div class="pt-4 border-t border-gray-100">
                        <p class="text-xs text-gray-500 text-center italic">This report represents all fees billed
                            (invoiced) within the selected date range, regardless of payment status.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
