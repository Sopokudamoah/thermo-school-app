<div class="flex items-center justify-end gap-1">
    <a href="{{ route('finance.expenses.show', $id) }}"
       class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-medium text-gray-600 hover:bg-gray-50 transition-colors border border-gray-200">
        <i data-lucide="eye" class="w-3.5 h-3.5"></i> View
    </a>
    @can('finance.expense.edit')
        @if($status === 'pending')
            <a href="{{ route('finance.expenses.edit', $id) }}"
               class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-medium text-indigo-600 hover:bg-indigo-50 transition-colors border border-gray-200">
                <i data-lucide="pencil" class="w-3.5 h-3.5"></i> Edit
            </a>
        @endif
    @endcan
</div>
