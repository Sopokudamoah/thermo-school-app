<div class="flex items-center justify-end gap-1">
    <a href="{{ route('finance.payments.show', $id) }}"
       class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-medium text-gray-600 hover:bg-gray-50 transition-colors border border-gray-200">
        <i data-lucide="eye" class="w-3.5 h-3.5"></i> View
    </a>
    <a href="{{ route('finance.payments.receipt', $id) }}" target="_blank"
       class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-medium text-gray-600 hover:bg-gray-50 transition-colors border border-gray-200">
        <i data-lucide="printer" class="w-3.5 h-3.5"></i> Receipt
    </a>
</div>
