@extends('layouts.app')

@section('page-title', 'Create Invoice')

@section('content')

    <div class="mb-6">
        <h1 class="font-heading text-xl font-bold text-gray-900">Create Invoice</h1>
        <nav class="flex items-center gap-1.5 mt-1 text-sm text-gray-500">
            <a href="{{ route('home') }}" class="hover:text-indigo-600 transition-colors">Home</a>
            <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
            <a href="{{ route('finance.dashboard') }}" class="hover:text-indigo-600 transition-colors">Finance</a>
            <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
            <a href="{{ route('finance.invoices.index') }}" class="hover:text-indigo-600 transition-colors">Invoices</a>
            <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
            <span class="text-gray-900">Create Invoice</span>
        </nav>
    </div>

    @include('session-messages')

    <div class="bg-white rounded-card shadow-card border border-gray-200 p-6" x-data="{
    student_id: '',
    fee_structures: {{ json_encode($fee_structures->map(fn($s) => ['id' => $s->id, 'name' => $s->name, 'items' => $s->items->map(fn($i) => ['fee_type_id' => $i->fee_type_id, 'amount' => $i->amount, 'description' => $i->feeType->name])])) }},
    items: [],

    addItem() {
        this.items.push({ fee_type_id: '', amount: 0, description: '' });
    },
    removeItem(index) {
        this.items.splice(index, 1);
    },
    applyStructure(id) {
        if (!id) return;
        const structure = this.fee_structures.find(s => s.id == id);
        if (structure) {
            structure.items.forEach(item => {
                this.items.push({ fee_type_id: item.fee_type_id, amount: item.amount, description: item.description });
            });
        }
    },
    total() {
        return this.items.reduce((sum, item) => sum + parseFloat(item.amount || 0), 0);
    }
}">
        <form action="{{ route('finance.invoices.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div>
                    <label for="student_id" class="block text-sm font-medium text-gray-700 mb-1.5">Student <sup
                            class="text-indigo-500">*</sup></label>
                    <select id="student_id" name="student_id" x-model="student_id" required
                            class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 bg-white focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                        <option value="">Select Student</option>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}">{{ $student->first_name }} {{ $student->last_name }}
                                ({{ $student->id }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="session_id" class="block text-sm font-medium text-gray-700 mb-1.5">Academic Session <sup
                            class="text-indigo-500">*</sup></label>
                    <select id="session_id" name="session_id" required
                            class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 bg-white focus:outline-none focus:border-indigo-500">
                        <option value="">Select Session</option>
                        @foreach($sessions as $session)
                            <option
                                value="{{ $session->id }}" {{ old('session_id') == $session->id ? 'selected' : '' }}>{{ $session->session_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="issue_date" class="block text-sm font-medium text-gray-700 mb-1.5">Issue Date <sup
                            class="text-indigo-500">*</sup></label>
                    <input type="date" id="issue_date" name="issue_date" value="{{ old('issue_date', date('Y-m-d')) }}"
                           required
                           class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 transition-colors">
                </div>

                <div>
                    <label for="due_date" class="block text-sm font-medium text-gray-700 mb-1.5">Due Date <sup
                            class="text-indigo-500">*</sup></label>
                    <input type="date" id="due_date" name="due_date"
                           value="{{ old('due_date', date('Y-m-d', strtotime('+30 days'))) }}" required
                           class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 transition-colors">
                </div>
            </div>

            <div class="mb-8 p-4 bg-indigo-50 rounded-lg border border-indigo-100">
                <label class="block text-sm font-semibold text-indigo-900 mb-2">Apply Fee Structure</label>
                <div class="flex items-center gap-3">
                    <select @change="applyStructure($event.target.value); $event.target.value = ''"
                            class="block flex-1 rounded-lg border border-indigo-200 px-3.5 py-2 text-sm text-gray-900 bg-white focus:outline-none focus:border-indigo-500">
                        <option value="">Select a structure to add its items...</option>
                        @foreach($fee_structures as $structure)
                            <option value="{{ $structure->id }}">{{ $structure->name }}
                                ({{ $structure->session->session_name }})
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-indigo-600">This will add all items from the structure to the list below.</p>
                </div>
            </div>

            <div class="mb-8">
                <h3 class="font-heading text-sm font-semibold text-gray-700 uppercase tracking-wider mb-4 pb-2 border-b border-gray-100 flex items-center justify-between">
                    Invoice Items
                    <button type="button" @click="addItem()"
                            class="text-indigo-600 hover:text-indigo-800 normal-case text-xs font-bold flex items-center gap-1">
                        <i data-lucide="plus-circle" class="w-3.5 h-3.5"></i> Add Custom Item
                    </button>
                </h3>

                <div class="space-y-3">
                    <template x-for="(item, index) in items" :key="index">
                        <div class="flex items-end gap-4 bg-gray-50 p-4 rounded-lg border border-gray-100">
                            <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label :for="'fee_type_' + index"
                                           class="block text-xs font-medium text-gray-600 mb-1">Fee Type</label>
                                    <select :name="'items[' + index + '][fee_type_id]'" :id="'fee_type_' + index"
                                            x-model="item.fee_type_id" required
                                            class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 bg-white focus:outline-none focus:border-indigo-500">
                                        <option value="">Select Fee Type</option>
                                        @foreach($fee_types as $type)
                                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label :for="'description_' + index"
                                           class="block text-xs font-medium text-gray-600 mb-1">Description</label>
                                    <input type="text" :name="'items[' + index + '][description]'"
                                           :id="'description_' + index" x-model="item.description" required
                                           class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 focus:outline-none focus:border-indigo-500"
                                           placeholder="Item description...">
                                </div>
                            </div>
                            <div class="w-32">
                                <label :for="'amount_' + index" class="block text-xs font-medium text-gray-600 mb-1">Amount</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                                        <span class="text-gray-500 text-xs">$</span>
                                    </div>
                                    <input type="number" step="0.01" min="0" :name="'items[' + index + '][amount]'"
                                           :id="'amount_' + index" x-model="item.amount" required
                                           class="block w-full rounded-lg border border-gray-300 pl-5 pr-2 py-2 text-sm text-gray-900 focus:outline-none focus:border-indigo-500">
                                </div>
                            </div>
                            <button type="button" @click="removeItem(index)"
                                    class="mb-1 p-2 text-gray-400 hover:text-red-500 transition-colors">
                                <i data-lucide="trash-2" class="w-5 h-5"></i>
                            </button>
                        </div>
                    </template>
                    <div x-show="items.length === 0"
                         class="py-10 text-center border-2 border-dashed border-gray-200 rounded-lg text-gray-500">
                        No items added yet. Apply a structure or add custom items.
                    </div>
                </div>
            </div>

            <div class="flex flex-col items-end mb-8">
                <div class="w-64 space-y-2">
                    <div class="flex justify-between text-sm text-gray-600">
                        <span>Subtotal</span>
                        <span x-text="'$' + total().toFixed(2)"></span>
                    </div>
                    <div class="flex justify-between text-lg font-bold text-gray-900 pt-2 border-t border-gray-200">
                        <span>Total</span>
                        <span x-text="'$' + total().toFixed(2)"></span>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" :disabled="items.length === 0"
                        class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed text-white font-medium text-sm px-5 py-2.5 rounded-lg transition-colors shadow-sm">
                    <i data-lucide="file-check" class="w-4 h-4"></i> Create Invoice
                </button>
                <a href="{{ route('finance.invoices.index') }}"
                   class="inline-flex items-center gap-2 bg-white hover:bg-gray-50 text-gray-700 font-medium text-sm px-5 py-2.5 rounded-lg border border-gray-300 transition-colors">
                    Cancel
                </a>
            </div>
        </form>
    </div>

@endsection
