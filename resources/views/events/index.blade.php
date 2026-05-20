@extends('layouts.app')
@section('page-title', 'Create Events')

@section('content')
<div class="mb-6">
    <h1 class="font-heading text-xl font-bold text-gray-900"><i data-lucide="calendar-days" class="inline w-5 h-5 mr-2"></i> Create Events</h1>
</div>

<div class="bg-white rounded-card shadow-card border border-gray-200 p-6">
    @include('components.events.event-calendar', ['editable' => 'true', 'selectable' => 'true'])
</div>
@endsection
