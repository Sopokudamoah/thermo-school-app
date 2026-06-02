@extends('layouts.app')

@section('page-title', 'Assign Scholarship')

@section('content')

    <div class="mb-6">
        <h1 class="font-heading text-xl font-bold text-gray-900">Assign Scholarship</h1>
        <nav class="flex items-center gap-1.5 mt-1 text-sm text-gray-500">
            <a href="{{ route('home') }}" class="hover:text-indigo-600 transition-colors">Home</a>
            <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
            <a href="{{ route('finance.dashboard') }}" class="hover:text-indigo-600 transition-colors">Finance</a>
            <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
            <a href="{{ route('finance.scholarships.index') }}" class="hover:text-indigo-600 transition-colors">Scholarships</a>
            <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
            <span class="text-gray-900">Assign Scholarship</span>
        </nav>
    </div>

    @include('session-messages')

    <div class="bg-white rounded-card shadow-card border border-gray-200 p-6 max-w-4xl">
        <form action="{{ route('finance.scholarships.assign.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="student_id" class="block text-sm font-medium text-gray-700 mb-1.5">Student <sup
                            class="text-indigo-500">*</sup></label>
                    <select id="student_id" name="student_id" required
                            onchange="window.location.href='{{ route('finance.scholarships.assign') }}?student_id=' + this.value"
                            class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 bg-white focus:outline-none focus:border-indigo-500 transition-colors">
                        <option value="">Select Student</option>
                        @foreach($students as $student)
                            <option
                                value="{{ $student->id }}" {{ $student_id == $student->id ? 'selected' : '' }}>{{ $student->first_name }} {{ $student->last_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="scholarship_id" class="block text-sm font-medium text-gray-700 mb-1.5">Scholarship Type
                        <sup class="text-indigo-500">*</sup></label>
                    <select id="scholarship_id" name="scholarship_id" required
                            class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 bg-white focus:outline-none focus:border-indigo-500 transition-colors">
                        <option value="">Select Scholarship</option>
                        @foreach($scholarships as $scholarship)
                            <option value="{{ $scholarship->id }}">{{ $scholarship->name }}
                                ({{ $scholarship->type == 'percentage' ? $scholarship->value . '%' : '$' . $scholarship->value }}
                                )
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="invoice_id" class="block text-sm font-medium text-gray-700 mb-1.5">Apply to Invoice
                        (Optional)</label>
                    <select id="invoice_id" name="invoice_id"
                            class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 bg-white focus:outline-none focus:border-indigo-500 transition-colors">
                        <option value="">Select Invoice</option>
                        @foreach($invoices as $invoice)
                            <option value="{{ $invoice->id }}">{{ $invoice->invoice_number }} -
                                ${{ number_format($invoice->total, 2) }} ({{ $invoice->status }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="approval_date" class="block text-sm font-medium text-gray-700 mb-1.5">Approval Date <sup
                            class="text-indigo-500">*</sup></label>
                    <input type="date" id="approval_date" name="approval_date" value="{{ date('Y-m-d') }}" required
                           class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 transition-colors">
                </div>

                <div>
                    <label for="valid_from" class="block text-sm font-medium text-gray-700 mb-1.5">Valid From <sup
                            class="text-indigo-500">*</sup></label>
                    <input type="date" id="valid_from" name="valid_from" value="{{ date('Y-m-d') }}" required
                           class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 transition-colors">
                </div>

                <div>
                    <label for="valid_until" class="block text-sm font-medium text-gray-700 mb-1.5">Valid Until
                        (Optional)</label>
                    <input type="date" id="valid_until" name="valid_until"
                           class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 transition-colors">
                </div>
            </div>

            <div class="mb-6">
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-1.5">Notes</label>
                <textarea id="notes" name="notes" rows="3"
                          placeholder="Enter any notes about this scholarship approval..."
                          class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 transition-colors"></textarea>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit"
                        class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm px-5 py-2.5 rounded-lg transition-colors shadow-sm">
                    <i data-lucide="check-circle" class="w-4 h-4"></i> Approve & Assign
                </button>
                <a href="{{ route('finance.scholarships.index') }}"
                   class="inline-flex items-center gap-2 bg-white hover:bg-gray-50 text-gray-700 font-medium text-sm px-5 py-2.5 rounded-lg border border-gray-300 transition-colors">
                    Cancel
                </a>
            </div>
        </form>
    </div>

@endsection
