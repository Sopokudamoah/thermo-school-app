<div class="flex items-center justify-end gap-3">
    <a href="{{ route('finance.budgets.show', $id) }}"
       class="text-indigo-600 hover:text-indigo-900 font-medium text-xs">
        View Variance Report
    </a>
    @can('finance.budget.manage')
        <a href="{{ route('finance.budgets.edit', $id) }}"
           class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-medium text-indigo-600 hover:bg-indigo-50 transition-colors border border-gray-200">
            <i data-lucide="pencil" class="w-3.5 h-3.5"></i> Edit
        </a>
    @endcan
</div>
