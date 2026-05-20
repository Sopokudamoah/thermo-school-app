@extends('layouts.app')
@section('page-title', 'Take Attendance')

@section('content')
<div class="mb-6">
    <h1 class="font-heading text-xl font-bold text-gray-900"><i data-lucide="calendar-check" class="inline w-5 h-5 mr-2"></i> Take Attendance</h1>
    <nav class="flex items-center gap-1.5 mt-1 text-sm text-gray-500">
        <a href="{{route('home')}}" class="hover:text-indigo-600">Home</a>
        <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
        <span>Take Attendance</span>
    </nav>
</div>

@include('session-messages')

<div class="mb-4">
    <h3 class="text-base font-semibold text-gray-800">
        Class #{{request()->query('class_name')}},
        @if ($academic_setting->attendance_type == 'course')
            Course: {{request()->query('course_name')}}
        @else
            Section #{{request()->query('section_name')}}
        @endif
    </h3>
    <p class="text-sm text-gray-500 mt-1">Current Date and Time: {{ date('Y-m-d H:i:s') }}</p>
</div>

<div class="bg-white rounded-card shadow-card border border-gray-200 p-4">
    <form action="{{route('attendances.store')}}" method="POST">
        @csrf
        <input type="hidden" name="session_id" value="{{$current_school_session_id}}">
        <input type="hidden" name="class_id" value="{{request()->query('class_id')}}">
        @if ($academic_setting->attendance_type == 'course')
            <input type="hidden" name="course_id" value="{{request()->query('course_id')}}">
            <input type="hidden" name="section_id" value="0">
        @else
            <input type="hidden" name="course_id" value="0">
            <input type="hidden" name="section_id" value="{{request()->query('section_id')}}">
        @endif
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200">
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider"># ID Card Number</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Student Name</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Present</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach ($student_list as $student)
                <input type="hidden" name="student_ids[]" value="{{$student->student_id}}">
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-4 py-3 text-gray-600 font-medium">{{$student->id_card_number}}</td>
                    <td class="px-4 py-3 text-gray-600">{{$student->student->first_name}} {{$student->student->last_name}}</td>
                    <td class="px-4 py-3">
                        <input class="w-4 h-4 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500" type="checkbox" name="status[{{$student->student_id}}]" checked>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @if(count($student_list) > 0 && $attendance_count < 1)
        <div class="mt-4">
            <button type="submit" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm px-4 py-2.5 rounded-lg transition-colors">
                <i data-lucide="check" class="w-4 h-4"></i> Submit
            </button>
        </div>
        @endif
    </form>
</div>
@endsection
