@extends('layouts.app')
@section('page-title', 'View Attendance')

@section('content')
<div class="mb-6">
    <h1 class="font-heading text-xl font-bold text-gray-900"><i data-lucide="calendar-check" class="inline w-5 h-5 mr-2"></i> View Attendance</h1>
    <nav class="flex items-center gap-1.5 mt-1 text-sm text-gray-500">
        <a href="{{route('home')}}" class="hover:text-indigo-600">Home</a>
        <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
        <span>View Attendance</span>
    </nav>
</div>

<div class="mb-4">
    <h5 class="text-base font-semibold text-gray-800">
        <i data-lucide="users" class="inline w-4 h-4 mr-1"></i> Student Name: {{$student->first_name}} {{$student->last_name}}
    </h5>
</div>

<div class="bg-white rounded-card shadow-card border border-gray-200 p-4 mb-5">
    <div id="attendanceCalendar"></div>
</div>

<div class="bg-white rounded-card shadow-card border border-gray-200">
    <table class="w-full text-sm">
        <thead>
            <tr class="bg-gray-50 border-b border-gray-200">
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Date</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Context</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @foreach ($attendances as $attendance)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-4 py-3">
                        @if ($attendance->status == "on")
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">PRESENT</span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">ABSENT</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-gray-600">{{$attendance->created_at}}</td>
                    <td class="px-4 py-3 text-gray-600">{{($attendance->section == null)?$attendance->course->course_name:$attendance->section->section_name}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

@php
$events = array();
if(count($attendances) > 0){
    foreach ($attendances as $attendance){
        if($attendance->status == "on"){
            $events[] = ['title'=> "Present", 'start' => $attendance->created_at, 'color'=>'green'];
        } else {
            $events[] = ['title'=> "Absent", 'start' => $attendance->created_at, 'color'=>'red'];
        }
    }
}
@endphp

@push('scripts')
<link rel="stylesheet" href="{{ asset('css/fullcalendar5.9.0.min.css') }}">
<script src="{{ asset('js/fullcalendar5.9.0.main.min.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('attendanceCalendar');
    var attEvents = @json($events);

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        height: 350,
        events: attEvents,
    });
    calendar.render();
});
</script>
@endpush
