{{-- Global Modals Container --}}
<div x-show="activeModal"
     class="fixed inset-0 z-[60]"
     style="display: none;"
     x-cloak>
    @stack('modals')
</div>
