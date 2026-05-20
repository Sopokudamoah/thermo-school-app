@extends('layouts.app')
@section('page-title', 'Course Marks')

@section('content')
<div class="mb-6">
    <h1 class="font-heading text-xl font-bold text-gray-900"><i data-lucide="table" class="inline w-5 h-5 mr-2"></i> Course Marks</h1>
    <nav class="flex items-center gap-1.5 mt-1 text-sm text-gray-500">
        <a href="{{route('home')}}" class="hover:text-indigo-600">Home</a>
        <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
        <a href="{{url()->previous()}}" class="hover:text-indigo-600">My Courses</a>
        <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
        <span>Course Marks</span>
    </nav>
</div>

<p class="text-sm font-semibold text-gray-700 mb-4">Course: {{$course_name}}</p>

<div class="bg-white rounded-card shadow-card border border-gray-200 mb-5">
    <table class="w-full text-sm">
        <thead>
            <tr class="bg-gray-50 border-b border-gray-200">
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Exam Name</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Marks</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @foreach ($marks as $mark)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-4 py-3 text-gray-600">{{$mark->exam->exam_name}}</td>
                    <td class="px-4 py-3 text-gray-600">{{$mark->marks}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@if(count($final_marks) > 0)
<h5 class="text-base font-semibold text-gray-800 mb-3">Final Result</h5>
<div class="bg-white rounded-card shadow-card border border-gray-200">
    <table class="w-full text-sm">
        <thead>
            <tr class="bg-gray-50 border-b border-gray-200">
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Marks</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Grade Points</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Grade</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @isset($final_marks)
                @foreach ($final_marks as $mark)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-4 py-3 text-gray-600">{{$mark->final_marks}}</td>
                    <td class="px-4 py-3 text-gray-600">{{$mark->getAttribute('point')}}</td>
                    <td class="px-4 py-3">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-700">{{$mark->getAttribute('grade')}}</span>
                    </td>
                </tr>
                @endforeach
            @endisset
        </tbody>
    </table>
</div>
@endif
@endsection
