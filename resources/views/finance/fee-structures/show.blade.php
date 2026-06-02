@extends('layouts.app')

@section('page-title', 'Fee Structure Details')

@section('content')

    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="font-heading text-xl font-bold text-gray-900">{{ $fee_structure->name }}</h1>
            <nav class="flex items-center gap-1.5 mt-1 text-sm text-gray-500">
                <a href="{{ route('home') }}" class="hover:text-indigo-600 transition-colors">Home</a>
                <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                <a href="{{ route('finance.dashboard') }}" class="hover:text-indigo-600 transition-colors">Finance</a>
                <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                <a href="{{ route('finance.fee-structures.index') }}" class="hover:text-indigo-600 transition-colors">Fee
                    Structures</a>
                <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                <span class="text-gray-900">Details</span>
            </nav>
        </div>
        <div class="flex items-center gap-3">
            @can('finance.fee-structure.edit')
                <a href="{{ route('finance.fee-structures.edit', $fee_structure->id) }}"
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 transition-colors">
                    <i data-lucide="pencil" class="w-4 h-4"></i> Edit
                </a>
            @endcan
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-card shadow-card border border-gray-200 p-6">
                <h3 class="font-heading text-sm font-semibold text-gray-700 uppercase tracking-wider mb-4 pb-2 border-b border-gray-100">
                    Information</h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-semibold">Session</p>
                        <p class="text-sm font-medium text-gray-900">{{ $fee_structure->session->session_name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-semibold">Class</p>
                        <p class="text-sm font-medium text-gray-900">{{ $fee_structure->schoolClass->class_name ?? 'All Classes' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-semibold">Section</p>
                        <p class="text-sm font-medium text-gray-900">{{ $fee_structure->section->section_name ?? 'All Sections' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-semibold">Semester</p>
                        <p class="text-sm font-medium text-gray-900">{{ $fee_structure->semester->semester_name ?? 'Full Academic Year' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2">
            <div class="bg-white rounded-card shadow-card border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-heading text-sm font-semibold text-gray-700 uppercase tracking-wider">Fee Items</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead>
                        <tr class="bg-gray-50 border-b border-gray-200">
                            <th class="px-6 py-3 font-semibold text-gray-700">Fee Type</th>
                            <th class="px-6 py-3 font-semibold text-gray-700">Code</th>
                            <th class="px-6 py-3 font-semibold text-gray-700 text-right">Amount</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                        @foreach($fee_structure->items as $item)
                            <tr>
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $item->feeType->name }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ $item->feeType->code }}</td>
                                <td class="px-6 py-4 text-right font-semibold text-gray-900">
                                    ${{ number_format($item->amount, 2) }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr class="bg-gray-50">
                            <td colspan="2" class="px-6 py-4 font-bold text-gray-900 text-right">Total</td>
                            <td class="px-6 py-4 text-right font-bold text-indigo-600 text-lg">
                                ${{ number_format($fee_structure->items->sum('amount'), 2) }}</td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection
