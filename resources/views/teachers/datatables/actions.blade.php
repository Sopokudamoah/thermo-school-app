<div class="flex items-center justify-end gap-1">
    <a href="{{ url('teachers/view/profile/'.$id) }}"
       class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 transition-colors border border-gray-200">
        <i data-lucide="eye" class="w-3.5 h-3.5"></i> Profile
    </a>
    @can('assign teachers')
        <button type="button"
                @click="$dispatch('open-assign-modal', { id: {{ $id }}, name: '{{ $first_name }} {{ $last_name }}' })"
                class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 transition-colors border border-gray-200">
            <i data-lucide="user-plus" class="w-3.5 h-3.5"></i> Assign
        </button>
    @endcan
    @can('edit users')
        <a href="{{ route('teacher.edit.show', ['id' => $id]) }}"
           class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 transition-colors border border-gray-200">
            <i data-lucide="pencil" class="w-3.5 h-3.5"></i> Edit
        </a>
    @endcan
</div>
