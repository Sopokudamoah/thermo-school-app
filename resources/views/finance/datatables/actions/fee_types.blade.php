<div class="flex items-center justify-end gap-1">
    @can('finance.fee-type.edit')
        <a href="{{ route('finance.fee-types.edit', $id) }}"
           class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-medium text-indigo-600 hover:bg-indigo-50 transition-colors border border-gray-200">
            <i data-lucide="pencil" class="w-3.5 h-3.5"></i> Edit
        </a>
    @endcan
</div>
