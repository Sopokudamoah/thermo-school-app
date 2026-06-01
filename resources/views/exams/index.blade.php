@extends('layouts.app')
@section('page-title', 'Exams')

@section('content')
<div class="mb-6">
    <h1 class="font-heading text-xl font-bold text-gray-900"><i data-lucide="file-text" class="inline w-5 h-5 mr-2"></i> Exams</h1>
    <nav class="flex items-center gap-1.5 mt-1 text-sm text-gray-500">
        <a href="{{route('home')}}" class="hover:text-indigo-600">Home</a>
        <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
        <span>Exam</span>
    </nav>
</div>

<div class="bg-white rounded-card shadow-card border border-gray-200 p-4 mb-5">
    <p class="text-sm font-medium text-gray-600 mb-3">Filter list by:</p>
    <form action="{{route('exam.list.show')}}" method="GET">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <div>
                <select class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 bg-white" aria-label="Class" name="class_id">
                    @isset($classes)
                        @foreach ($classes as $school_class)
                            <option value="{{$school_class->id}}">{{$school_class->class_name}}</option>
                        @endforeach
                    @endisset
                </select>
            </div>
            <div>
                <select class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 bg-white" aria-label="Status" name="semester_id">
                    <option value=""
                            disabled {{ (!isset($semester_id) || $semester_id == 0) && !isset($academic_setting->active_semester_id) ? 'selected' : '' }}>
                        Select Semester
                    </option>
                    @isset($semesters)
                        @foreach ($semesters as $semester)
                            <option
                                value="{{$semester->id}}" {{ (isset($semester_id) && $semester_id == $semester->id) || (!isset($semester_id) || $semester_id == 0) && isset($academic_setting->active_semester_id) && $academic_setting->active_semester_id == $semester->id ? 'selected' : '' }}>{{$semester->semester_name}}</option>
                        @endforeach
                    @endisset
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm px-4 py-2.5 rounded-lg transition-colors">
                    <i data-lucide="refresh-cw" class="w-4 h-4"></i> Load List
                </button>
            </div>
        </div>
    </form>
</div>

<div class="bg-white rounded-card shadow-card border border-gray-200">
    <table class="w-full text-sm">
        <thead>
            <tr class="bg-gray-50 border-b border-gray-200">
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Name</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Course</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Created at</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Starts</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Ends</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @foreach ($exams as $exam)
                @if (Auth::user()->role == "admin")
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-4 py-3 text-gray-600">{{$exam->exam_name}}</td>
                    <td class="px-4 py-3 text-gray-600">{{$exam->course->course_name}}</td>
                    <td class="px-4 py-3 text-gray-600">{{$exam->created_at}}</td>
                    <td class="px-4 py-3 text-gray-600">{{$exam->start_date}}</td>
                    <td class="px-4 py-3 text-gray-600">{{$exam->end_date}}</td>
                    <td class="px-4 py-3">
                        <div class="flex gap-2">
                            <a href="{{route('exam.rule.create', ['exam_id' => $exam->id])}}" class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 transition-colors border border-gray-200">
                                <i data-lucide="plus" class="w-3.5 h-3.5"></i> Add Rule
                            </a>
                            <a href="{{route('exam.rule.show', ['exam_id' => $exam->id])}}" class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 transition-colors border border-gray-200">
                                <i data-lucide="eye" class="w-3.5 h-3.5"></i> View Rule
                            </a>
                        </div>
                    </td>
                </tr>
                @elseif(Auth::user()->role == "teacher")
                    @foreach ($teacher_courses as $teacher_course)
                        @if ($exam->course->id != $teacher_course->course_id)
                            @continue
                        @else
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3 text-gray-600">{{$exam->exam_name}}</td>
                            <td class="px-4 py-3 text-gray-600">{{$exam->course->course_name}}</td>
                            <td class="px-4 py-3 text-gray-600">{{$exam->created_at}}</td>
                            <td class="px-4 py-3 text-gray-600">{{$exam->start_date}}</td>
                            <td class="px-4 py-3 text-gray-600">{{$exam->end_date}}</td>
                            <td class="px-4 py-3">
                                <div class="flex gap-2">
                                    <a href="{{route('exam.rule.create', ['exam_id' => $exam->id])}}" class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 transition-colors border border-gray-200">
                                        <i data-lucide="plus" class="w-3.5 h-3.5"></i> Add Rule
                                    </a>
                                    <a href="{{route('exam.rule.show', ['exam_id' => $exam->id])}}" class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 transition-colors border border-gray-200">
                                        <i data-lucide="eye" class="w-3.5 h-3.5"></i> View Rule
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endif
                    @endforeach
                @endif
            @endforeach
        </tbody>
    </table>
</div>
@endsection
