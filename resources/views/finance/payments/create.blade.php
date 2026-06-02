@extends('layouts.app')

@section('page-title', 'Record Payment')

@section('content')

    <div class="mb-6">
        <h1 class="font-heading text-xl font-bold text-gray-900">Record Payment</h1>
        <nav class="flex items-center gap-1.5 mt-1 text-sm text-gray-500">
            <a href="{{ route('home') }}" class="hover:text-indigo-600 transition-colors">Home</a>
            <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
            <a href="{{ route('finance.dashboard') }}" class="hover:text-indigo-600 transition-colors">Finance</a>
            <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
            <a href="{{ route('finance.payments.index') }}" class="hover:text-indigo-600 transition-colors">Payments</a>
            <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
            <span class="text-gray-900">Record Payment</span>
        </nav>
    </div>

    @include('session-messages')

    <div class="bg-white rounded-card shadow-card border border-gray-200 p-6" x-data="{
    student_id: '{{ $student->id ?? '' }}',
    amount: 0,
    invoices: {{ json_encode($outstanding_invoices) }},
    allocations: [],
    loadingInvoices: false,

    fetchInvoices() {
        if (!this.student_id) {
            this.invoices = [];
            this.allocations = [];
            return;
        }
        this.loadingInvoices = true;
        fetch('{{ route('finance.payments.student-invoices') }}?student_id=' + this.student_id)
            .then(res => res.json())
            .then(data => {
                this.invoices = data;
                this.allocations = data.map(inv => ({
                    invoice_id: inv.id,
                    invoice_number: inv.invoice_number,
                    balance: inv.balance,
                    amount: 0
                }));
                this.loadingInvoices = false;
            });
    },

    autoAllocate() {
        let remaining = parseFloat(this.amount || 0);
        this.allocations.forEach(alc => {
            const canPay = Math.min(remaining, alc.balance);
            alc.amount = canPay.toFixed(2);
            remaining -= canPay;
        });
    },

    totalAllocated() {
        return this.allocations.reduce((sum, alc) => sum + parseFloat(alc.amount || 0), 0);
    }
}" x-init="if(student_id) { fetchInvoices(); }">
        <form action="{{ route('finance.payments.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="lg:col-span-1">
                    <label for="student_id" class="block text-sm font-medium text-gray-700 mb-1.5">Student <sup
                            class="text-indigo-500">*</sup></label>
                    <select id="student_id" name="student_id" x-model="student_id" @change="fetchInvoices()" required
                            class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 bg-white focus:outline-none focus:border-indigo-500">
                        <option value="">Select Student</option>
                        @foreach($students as $s)
                            <option value="{{ $s->id }}">{{ $s->first_name }} {{ $s->last_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="payment_date" class="block text-sm font-medium text-gray-700 mb-1.5">Payment Date <sup
                            class="text-indigo-500">*</sup></label>
                    <input type="date" id="payment_date" name="payment_date"
                           value="{{ old('payment_date', date('Y-m-d')) }}" required
                           class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500">
                </div>

                <div>
                    <label for="method" class="block text-sm font-medium text-gray-700 mb-1.5">Payment Method <sup
                            class="text-indigo-500">*</sup></label>
                    <select id="method" name="method" required
                            class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 bg-white focus:outline-none focus:border-indigo-500">
                        @foreach($methods as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="amount" class="block text-sm font-medium text-gray-700 mb-1.5">Amount Received <sup
                            class="text-indigo-500">*</sup></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 text-sm">$</span>
                        </div>
                        <input type="number" step="0.01" min="0" id="amount" name="amount" x-model="amount"
                               @input="autoAllocate()" required
                               class="block w-full rounded-lg border border-gray-300 pl-7 pr-3 py-2.5 text-sm text-gray-900 font-bold focus:outline-none focus:border-indigo-500"
                               placeholder="0.00">
                    </div>
                </div>
            </div>

            <div class="mb-8">
                <label for="reference" class="block text-sm font-medium text-gray-700 mb-1.5">Reference / Notes</label>
                <input type="text" id="reference" name="reference"
                       placeholder="e.g. Check #123, Bank Transfer Ref, etc."
                       class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500">
            </div>

            <div class="mb-8">
                <h3 class="font-heading text-sm font-semibold text-gray-700 uppercase tracking-wider mb-4 pb-2 border-b border-gray-100 flex items-center justify-between">
                    Invoice Allocation
                    <span class="text-xs font-normal text-gray-500" x-show="loadingInvoices">Loading invoices...</span>
                </h3>

                <div class="overflow-x-auto border border-gray-200 rounded-lg">
                    <table class="w-full text-left text-sm">
                        <thead>
                        <tr class="bg-gray-50 border-b border-gray-200">
                            <th class="px-4 py-3 font-semibold text-gray-700">Invoice #</th>
                            <th class="px-4 py-3 font-semibold text-gray-700">Outstanding Balance</th>
                            <th class="px-4 py-3 font-semibold text-gray-700 w-48">Payment Amount</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                        <template x-for="(alc, index) in allocations" :key="index">
                            <tr>
                                <td class="px-4 py-3 text-gray-900 font-medium" x-text="alc.invoice_number"></td>
                                <td class="px-4 py-3 text-gray-600" x-text="'$' + alc.balance.toFixed(2)"></td>
                                <td class="px-4 py-3">
                                    <div class="relative">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-400 text-xs">$</span>
                                        </div>
                                        <input type="number" step="0.01" min="0" :max="alc.balance"
                                               :name="'allocations[' + index + '][amount]'" x-model="alc.amount"
                                               class="block w-full rounded-lg border border-gray-200 pl-6 pr-3 py-1.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500">
                                        <input type="hidden" :name="'allocations[' + index + '][invoice_id]'"
                                               :value="alc.invoice_id">
                                    </div>
                                </td>
                            </tr>
                        </template>
                        <tr x-show="allocations.length === 0 && !loadingInvoices">
                            <td colspan="3" class="px-4 py-10 text-center text-gray-500 italic">
                                No outstanding invoices for this student.
                            </td>
                        </tr>
                        </tbody>
                        <tfoot class="bg-gray-50" x-show="allocations.length > 0">
                        <tr>
                            <td colspan="2" class="px-4 py-3 text-right font-bold text-gray-900">Total Allocated</td>
                            <td class="px-4 py-3 text-indigo-600 font-black"
                                x-text="'$' + totalAllocated().toFixed(2)"></td>
                        </tr>
                        <tr x-show="Math.abs(totalAllocated() - amount) > 0.01">
                            <td colspan="3" class="px-4 py-2 bg-amber-50 text-amber-700 text-xs font-medium">
                                Warning: Allocated total ($<span x-text="totalAllocated().toFixed(2)"></span>) does not
                                match received amount ($<span x-text="parseFloat(amount || 0).toFixed(2)"></span>).
                            </td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" :disabled="amount <= 0 || allocations.length === 0"
                        class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed text-white font-medium text-sm px-5 py-2.5 rounded-lg transition-colors shadow-sm">
                    <i data-lucide="check-circle" class="w-4 h-4"></i> Record Payment
                </button>
                <a href="{{ route('finance.payments.index') }}"
                   class="inline-flex items-center gap-2 bg-white hover:bg-gray-50 text-gray-700 font-medium text-sm px-5 py-2.5 rounded-lg border border-gray-300 transition-colors">
                    Cancel
                </a>
            </div>
        </form>
    </div>

@endsection
