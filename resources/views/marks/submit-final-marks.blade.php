@extends('layouts.app')
@section('page-title', 'Give Final Marks')

@section('content')
<div class="mb-6">
    <h1 class="font-heading text-xl font-bold text-gray-900"><i data-lucide="table" class="inline w-5 h-5 mr-2"></i> Give Final Marks</h1>
    <nav class="flex items-center gap-1.5 mt-1 text-sm text-gray-500">
        <a href="{{route('home')}}" class="hover:text-indigo-600">Home</a>
        <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
        <a href="{{route('course.mark.create')}}" class="hover:text-indigo-600">Give marks</a>
        <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
        <span>Final Marks</span>
    </nav>
</div>

@include('session-messages')

<div class="mb-4 bg-white rounded-card shadow-card border border-gray-200 p-4">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div>
            <span class="block text-xs font-semibold text-gray-500 uppercase tracking-wider">Semester</span>
            <span class="text-sm font-medium text-gray-900">{{$semester_name}}</span>
        </div>
        <div>
            <span class="block text-xs font-semibold text-gray-500 uppercase tracking-wider">Class</span>
            <span class="text-sm font-medium text-gray-900">{{$class_name}}</span>
        </div>
        <div>
            <span class="block text-xs font-semibold text-gray-500 uppercase tracking-wider">Section</span>
            <span class="text-sm font-medium text-gray-900">{{$section_name}}</span>
        </div>
        <div>
            <span class="block text-xs font-semibold text-gray-500 uppercase tracking-wider">Course</span>
            <span class="text-sm font-medium text-gray-900">{{$course_name}}</span>
        </div>
    </div>
</div>

<form action="{{route('course.final.mark.submit.store')}}" method="POST">
    @csrf
    <input type="hidden" name="session_id" value="{{$current_school_session_id}}">
    <div class="bg-white rounded-card shadow-card border border-gray-200 overflow-x-auto mb-4">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200">
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Student Name</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Calculated Marks</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Final Marks</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Note</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @isset($students_with_marks)
                    @foreach ($students_with_marks as $id => $students_with_mark)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-3 text-gray-600">{{$students_with_mark[0]->student->first_name}} {{$students_with_mark[0]->student->last_name}}</td>
                        @php
                            $calculated_marks = 0;
                        @endphp
                        @foreach ($students_with_mark as $st)
                            @php
                                $calculated_marks += $st->marks;
                            @endphp
                        @endforeach
                        <td class="px-4 py-3">
                            <input type="number" step="0.01" class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100" name="calculated_mark[{{$students_with_mark[0]->student->id}}]" value="{{$calculated_marks}}" readonly>
                        </td>
                        <td class="px-4 py-3">
                            <input type="number" step="0.01" class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100" name="final_mark[{{$students_with_mark[0]->student->id}}]" required>
                        </td>
                        <td class="px-4 py-3">
                            <textarea class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100" rows="1" name="note[{{$students_with_mark[0]->student->id}}]" placeholder="Counted best 2 Quizes from 3,..."></textarea>
                        </td>
                    </tr>
                    @endforeach
                @endisset
                <input type="hidden" name="semester_id" value="{{$semester_id}}">
                <input type="hidden" name="class_id" value="{{$class_id}}">
                <input type="hidden" name="section_id" value="{{$section_id}}">
                <input type="hidden" name="course_id" value="{{$course_id}}">
            </tbody>
        </table>
    </div>
    <div>
        <button type="submit" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm px-4 py-2.5 rounded-lg transition-colors">
            <i data-lucide="check" class="w-4 h-4"></i> Save
        </button>
    </div>
</form>
@endsection
