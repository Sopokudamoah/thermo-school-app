@if (session('status'))
<div class="flex items-start gap-2.5 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-lg px-4 py-3 mb-4 text-sm">
    <i data-lucide="check-circle" class="w-4 h-4 shrink-0 mt-0.5"></i>
    <span>{{ session('status') }}</span>
</div>
@endif

@if (session('error'))
<div class="flex items-start gap-2.5 bg-red-50 border border-red-200 text-red-800 rounded-lg px-4 py-3 mb-4 text-sm">
    <i data-lucide="alert-circle" class="w-4 h-4 shrink-0 mt-0.5"></i>
    <span>{{ session('error') }}</span>
</div>
@endif

@if ($errors->any())
<div class="bg-red-50 border border-red-200 rounded-lg px-4 py-3 mb-4">
    @foreach ($errors->all() as $error)
    <p class="flex items-start gap-2.5 text-sm text-red-800 {{ !$loop->last ? 'mb-1.5' : '' }}">
        <i data-lucide="alert-circle" class="w-4 h-4 shrink-0 mt-0.5"></i>
        <span>{{ $error }}</span>
    </p>
    @endforeach
</div>
@endif
