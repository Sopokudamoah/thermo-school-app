@extends('layouts.app')
@section('page-title', 'View Attendance')

@section('content')
<div class="mb-6">
    <h1 class="font-heading text-xl font-bold text-gray-900"><i data-lucide="calendar-check" class="inline w-5 h-5 mr-2"></i> View Attendance</h1>
    <nav class="flex items-center gap-1.5 mt-1 text-sm text-gray-500">
        <a href="{{route('home')}}" class="hover:text-indigo-600">Home</a>
        <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
        <a href="{{url()->previous()}}" class="hover:text-indigo-600">Courses</a>
        <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
        <span>View Attendance</span>
    </nav>
</div>

@if(request()->query('course_name'))
    <h3 class="text-base font-semibold text-gray-800 mb-1">Course: {{request()->query('course_name')}}</h3>
@elseif(request()->query('section_name'))
    <h3 class="text-base font-semibold text-gray-800 mb-1">Section: {{request()->query('section_name')}}</h3>
@endif
<p class="text-sm text-gray-500 mb-4">Current Date and Time: {{ date('Y-m-d H:i:s') }}</p>

<div class="bg-white rounded-card shadow-card border border-gray-200">
    <table class="w-full text-sm">
        <thead>
            <tr class="bg-gray-50 border-b border-gray-200">
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Student Name</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Today's Status</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Attended</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @foreach ($attendances as $attendance)
                @php
                    $total_attended = \App\Models\Attendance::where('student_id', $attendance->student_id)->where('session_id', $attendance->session_id)->count();
                @endphp
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-4 py-3 text-gray-600">{{$attendance->student->first_name}} {{$attendance->student->last_name}}</td>
                    <td class="px-4 py-3">
                        @if ($attendance->status == "on")
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">PRESENT</span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">ABSENT</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-gray-600">{{$total_attended}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
