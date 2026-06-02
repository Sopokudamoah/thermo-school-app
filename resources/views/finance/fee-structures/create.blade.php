@extends('layouts.app')

@section('page-title', 'Add Fee Structure')

@section('content')

    <div class="mb-6">
        <h1 class="font-heading text-xl font-bold text-gray-900">Add Fee Structure</h1>
        <nav class="flex items-center gap-1.5 mt-1 text-sm text-gray-500">
            <a href="{{ route('home') }}" class="hover:text-indigo-600 transition-colors">Home</a>
            <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
            <a href="{{ route('finance.dashboard') }}" class="hover:text-indigo-600 transition-colors">Finance</a>
            <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
            <a href="{{ route('finance.fee-structures.index') }}" class="hover:text-indigo-600 transition-colors">Fee
                Structures</a>
            <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
            <span class="text-gray-900">Add Fee Structure</span>
        </nav>
    </div>

    @include('session-messages')

    <div class="bg-white rounded-card shadow-card border border-gray-200 p-6" x-data="{
    items: [{ fee_type_id: '', amount: '' }],
    addItem() {
        this.items.push({ fee_type_id: '', amount: '' });
    },
    removeItem(index) {
        if (this.items.length > 1) {
            this.items.splice(index, 1);
        }
    }
}">
        <form action="{{ route('finance.fee-structures.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                <div class="lg:col-span-2">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">Structure Name <sup
                            class="text-indigo-500">*</sup></label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}"
                           placeholder="e.g. Grade 10 - 2024 Academic Year" required
                           class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-colors">
                </div>

                <div>
                    <label for="session_id" class="block text-sm font-medium text-gray-700 mb-1.5">Academic Session <sup
                            class="text-indigo-500">*</sup></label>
                    <select id="session_id" name="session_id" required
                            class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 bg-white focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                        <option value="">Select Session</option>
                        @foreach($sessions as $session)
                            <option
                                value="{{ $session->id }}" {{ old('session_id') == $session->id ? 'selected' : '' }}>{{ $session->session_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="class_id" class="block text-sm font-medium text-gray-700 mb-1.5">Class <sup
                            class="text-indigo-500">*</sup></label>
                    <select id="class_id" name="class_id" required
                            class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 bg-white focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                        <option value="">Select Class</option>
                        @foreach($classes as $class)
                            <option
                                value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>{{ $class->class_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="section_id" class="block text-sm font-medium text-gray-700 mb-1.5">Section
                        (Optional)</label>
                    <select id="section_id" name="section_id"
                            class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 bg-white focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                        <option value="">All Sections</option>
                        @foreach($sections as $section)
                            <option
                                value="{{ $section->id }}" {{ old('section_id') == $section->id ? 'selected' : '' }}>{{ $section->section_name }}
                                ({{ $section->schoolClass->class_name }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="semester_id" class="block text-sm font-medium text-gray-700 mb-1.5">Semester
                        (Optional)</label>
                    <select id="semester_id" name="semester_id"
                            class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 bg-white focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                        <option value="">Full Academic Year</option>
                        @foreach($semesters as $semester)
                            <option
                                value="{{ $semester->id }}" {{ old('semester_id') == $semester->id ? 'selected' : '' }}>{{ $semester->semester_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mb-8">
                <h3 class="font-heading text-sm font-semibold text-gray-700 uppercase tracking-wider mb-4 pb-2 border-b border-gray-100 flex items-center justify-between">
                    Fee Items
                    <button type="button" @click="addItem()"
                            class="text-indigo-600 hover:text-indigo-800 normal-case text-xs font-bold flex items-center gap-1">
                        <i data-lucide="plus-circle" class="w-3.5 h-3.5"></i> Add Item
                    </button>
                </h3>

                <div class="space-y-3">
                    <template x-for="(item, index) in items" :key="index">
                        <div class="flex items-end gap-4">
                            <div class="flex-1">
                                <label :for="'fee_type_' + index" class="block text-xs font-medium text-gray-600 mb-1">Fee
                                    Type</label>
                                <select :name="'items[' + index + '][fee_type_id]'" :id="'fee_type_' + index" required
                                        class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 bg-white focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                                    <option value="">Select Fee Type</option>
                                    @foreach($fee_types as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }} ({{ $type->code }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="w-48">
                                <label :for="'amount_' + index" class="block text-xs font-medium text-gray-600 mb-1">Amount</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 text-sm">$</span>
                                    </div>
                                    <input type="number" step="0.01" min="0" :name="'items[' + index + '][amount]'"
                                           :id="'amount_' + index" required
                                           class="block w-full rounded-lg border border-gray-300 pl-7 pr-3 py-2 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                                           placeholder="0.00">
                                </div>
                            </div>
                            <button type="button" @click="removeItem(index)"
                                    class="mb-2 p-2 text-gray-400 hover:text-red-500 transition-colors">
                                <i data-lucide="trash-2" class="w-5 h-5"></i>
                            </button>
                        </div>
                    </template>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit"
                        class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm px-5 py-2.5 rounded-lg transition-colors shadow-sm">
                    <i data-lucide="save" class="w-4 h-4"></i> Save Structure
                </button>
                <a href="{{ route('finance.fee-structures.index') }}"
                   class="inline-flex items-center gap-2 bg-white hover:bg-gray-50 text-gray-700 font-medium text-sm px-5 py-2.5 rounded-lg border border-gray-300 transition-colors">
                    Cancel
                </a>
            </div>
        </form>
    </div>

@endsection
