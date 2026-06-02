<div class="flex items-center justify-end gap-1">
    <a href="{{ route('finance.scholarships.assign') }}?scholarship_id={{ $id }}"
       class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-medium text-emerald-600 hover:bg-emerald-50 transition-colors border border-gray-200">
        <i data-lucide="user-plus" class="w-3.5 h-3.5"></i> Assign
    </a>
    @can('finance.scholarship.edit')
        <a href="{{ route('finance.scholarships.edit', $id) }}"
           class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-medium text-indigo-600 hover:bg-indigo-50 transition-colors border border-gray-200">
            <i data-lucide="pencil" class="w-3.5 h-3.5"></i> Edit
        </a>
    @endcan
</div>
