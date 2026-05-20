@extends('layouts.app')
@section('page-title', 'Routine')

@section('content')
<div class="mb-6">
    <h1 class="font-heading text-xl font-bold text-gray-900"><i data-lucide="clock" class="inline w-5 h-5 mr-2"></i> Routine</h1>
    <nav class="flex items-center gap-1.5 mt-1 text-sm text-gray-500">
        <a href="{{route('home')}}" class="hover:text-indigo-600">Home</a>
        <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
        <a href="{{url()->previous()}}" class="hover:text-indigo-600">Classes</a>
        <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
        <span>Section Routine</span>
    </nav>
</div>

@php
    function getDayName($weekday) {
        if($weekday == 1) {
            return "MONDAY";
        } else if($weekday == 2) {
            return "TUESDAY";
        } else if($weekday == 3) {
            return "WEDNESDAY";
        } else if($weekday == 4) {
            return "THURSDAY";
        } else if($weekday == 5) {
            return "FRIDAY";
        } else if($weekday == 6) {
            return "SATURDAY";
        } else if($weekday == 7) {
            return "SUNDAY";
        } else {
            return "Noday";
        }
    }
@endphp

@if(count($routines) > 0)
<div class="bg-white rounded-card shadow-card border border-gray-200 overflow-x-auto">
    <table class="w-full text-sm text-center">
        <tbody class="divide-y divide-gray-100">
            @foreach($routines as $day => $courses)
                <tr class="hover:bg-gray-50 transition-colors">
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider bg-gray-50 border-r border-gray-200 w-28">{{getDayName($day)}}</th>
                    @php
                        $courses = $courses->sortBy('start');
                    @endphp
                    @foreach($courses as $course)
                        <td class="px-4 py-3 text-gray-600 border-r border-gray-100 last:border-r-0">
                            <span class="block font-medium text-gray-800">{{$course->course->course_name}}</span>
                            <span class="text-xs text-gray-500">{{$course->start}} - {{$course->end}}</span>
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@else
<div class="flex flex-col items-center py-10 text-center">
    <i data-lucide="clock" class="w-8 h-8 text-gray-300 mb-2"></i>
    <p class="text-sm text-gray-500">No routine has been added yet.</p>
</div>
@endif
@endsection
