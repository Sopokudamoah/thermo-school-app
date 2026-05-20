@extends('layouts.app')
@section('page-title', 'Give Marks')

@section('content')
<div class="mb-6">
    <h1 class="font-heading text-xl font-bold text-gray-900"><i data-lucide="table" class="inline w-5 h-5 mr-2"></i> Give Marks</h1>
    <nav class="flex items-center gap-1.5 mt-1 text-sm text-gray-500">
        <a href="{{route('home')}}" class="hover:text-indigo-600">Home</a>
        <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
        <a href="{{url()->previous()}}" class="hover:text-indigo-600">My courses</a>
        <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
        <span>Give Marks</span>
    </nav>
</div>

@include('session-messages')

@if ($academic_setting['marks_submission_status'] == "on")
<div class="flex items-center gap-2 text-indigo-600 text-sm mb-3">
    <i data-lucide="alert-circle" class="w-4 h-4 flex-shrink-0"></i>
    <span>Marks Submission Window is open now.</span>
</div>
@endif
<div class="flex items-center gap-2 text-indigo-600 text-sm mb-3">
    <i data-lucide="alert-circle" class="w-4 h-4 flex-shrink-0"></i>
    <span>Final Marks submission should be done only once in a Semester when the Marks Submission Window is open.</span>
</div>
@if ($final_marks_submitted)
<div class="flex items-center gap-2 text-green-600 text-sm mb-3">
    <i data-lucide="alert-circle" class="w-4 h-4 flex-shrink-0"></i>
    <span>Marks are submitted.</span>
</div>
@endif

<div class="mb-4">
    <h3 class="text-base font-semibold text-gray-800">Class #{{request()->query('class_name')}}, Section #{{request()->query('section_name')}}</h3>
    <h3 class="text-base font-semibold text-gray-800">Course: {{request()->query('course_name')}}</h3>
</div>

@if (!$final_marks_submitted && count($exams) > 0 && $academic_setting['marks_submission_status'] == "on")
    <div class="mb-4">
        <a href="{{route('course.final.mark.submit.show', ['class_id' => $class_id, 'class_name' => request()->query('class_name'), 'section_id' => $section_id, 'section_name' => request()->query('section_name'), 'course_id' => $course_id, 'course_name' => request()->query('course_name'), 'semester_id' => $semester_id])}}"
           class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm px-4 py-2.5 rounded-lg transition-colors"
           onclick="return confirm('Are you sure, you want to submit final marks?')">
            <i data-lucide="check" class="w-4 h-4"></i> Submit Final Marks
        </a>
    </div>
@endif

<form action="{{route('course.mark.store')}}" method="POST">
    @csrf
    <input type="hidden" name="session_id" value="{{$current_school_session_id}}">
    <div class="bg-white rounded-card shadow-card border border-gray-200 overflow-x-auto mb-4">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200">
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Student Name</th>
                    @isset($exams)
                        @foreach ($exams as $exam)
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            <a href="{{route('exam.rule.show', ['exam_id' => $exam->id])}}" class="hover:text-indigo-600" title="View {{$exam->exam_name}} exam rules">{{$exam->exam_name}}</a>
                        </th>
                        @endforeach
                    @endisset
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @isset($exams)
                    @isset($students_with_marks)
                        @foreach ($students_with_marks as $id => $students_with_mark)
                            @php
                                $markedExamCount = 0;
                            @endphp
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3 text-gray-600">{{$students_with_mark[0]->student->first_name}} {{$students_with_mark[0]->student->last_name}}</td>
                            @foreach ($students_with_mark as $st)
                                <td class="px-4 py-3">
                                    <input type="number" step="0.01" class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100" name="student_mark[{{$students_with_mark[0]->student->id}}][{{$exams[$markedExamCount]->id}}]" value="{{$st->marks}}">
                                </td>
                                @php
                                    $markedExamCount++;
                                @endphp
                            @endforeach
                            @php
                                $students_with_markCount = count($students_with_mark);
                                $examCount = count($exams);
                                $gt = 0;
                                if($students_with_markCount < $examCount) {
                                    $gt = $examCount - $students_with_markCount;
                                }
                            @endphp
                            @for ($i = 0; $i < $gt; $i++)
                                <td class="px-4 py-3">
                                    <input type="number" step="0.01" class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100" name="student_mark[{{$students_with_mark[0]->student->id}}][{{$exams[$markedExamCount]->id}}]">
                                </td>
                                @php
                                    $markedExamCount++;
                                @endphp
                            @endfor
                        </tr>
                        @endforeach
                    @endisset
                @endisset
                @if(count($students_with_marks) < 1)
                    @foreach ($sectionStudents as $sectionStudent)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3 text-gray-600">{{$sectionStudent->student->first_name}} {{$sectionStudent->student->last_name}}</td>
                            @isset($exams)
                                @foreach ($exams as $exam)
                                    <td class="px-4 py-3">
                                        <input type="number" class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100" name="student_mark[{{$sectionStudent->student->id}}][{{$exam->id}}]">
                                    </td>
                                @endforeach
                            @endisset
                        </tr>
                    @endforeach
                @endif
                <input type="hidden" name="studentCount" value="{{count($sectionStudents)}}">
                <input type="hidden" name="semester_id" value="{{$semester_id}}">
                <input type="hidden" name="class_id" value="{{$class_id}}">
                <input type="hidden" name="section_id" value="{{$section_id}}">
                <input type="hidden" name="course_id" value="{{$course_id}}">
            </tbody>
        </table>
    </div>
    @if(!$final_marks_submitted && count($exams) > 0)
    <div>
        <button type="submit" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm px-4 py-2.5 rounded-lg transition-colors">
            <i data-lucide="check" class="w-4 h-4"></i> Save
        </button>
    </div>
    @else
        @if($final_marks_submitted)
        <div class="flex items-center gap-2 text-green-600 text-sm">
            <i data-lucide="alert-circle" class="w-4 h-4 flex-shrink-0"></i>
            <span>You have submitted Final Marks.</span>
        </div>
        @else
        <div class="flex items-center gap-2 text-indigo-600 text-sm">
            <i data-lucide="alert-circle" class="w-4 h-4 flex-shrink-0"></i>
            <span>Create Exam to give marks.</span>
        </div>
        @endif
    @endif
</form>
@endsection
