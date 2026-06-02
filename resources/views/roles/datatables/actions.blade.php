<div class="flex items-center justify-end gap-1">
    <a href="{{ route('roles.edit', $id) }}"
       class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 transition-colors border border-gray-200">
        <i data-lucide="pencil" class="w-3.5 h-3.5"></i> Edit
    </a>
    @if(!in_array($name, ['Administrator', 'Teacher', 'Finance']))
        <form action="{{ route('roles.destroy', $id) }}" method="POST"
              onsubmit="return confirm('Are you sure you want to delete this role?')">
            @csrf
            @method('DELETE')
            <button type="submit"
                    class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-medium text-red-600 hover:bg-red-50 transition-colors border border-red-100">
                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i> Delete
            </button>
        </form>
    @else
        <span class="text-xs text-gray-400 italic px-2.5 py-1.5">System Role</span>
    @endif
</div>
