@extends('layouts.app')
@section('page-title', 'View Grading Systems')

@section('content')
<div class="mb-6">
    <h1 class="font-heading text-xl font-bold text-gray-900"><i data-lucide="file-text" class="inline w-5 h-5 mr-2"></i> View Grading Systems</h1>
    <nav class="flex items-center gap-1.5 mt-1 text-sm text-gray-500">
        <a href="{{route('home')}}" class="hover:text-indigo-600">Home</a>
        <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
        <span>View Grading Systems</span>
    </nav>
</div>

<div class="bg-white rounded-card shadow-card border border-gray-200">
    <table class="w-full text-sm">
        <thead>
            <tr class="bg-gray-50 border-b border-gray-200">
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">System Name</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Class</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Semester</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Created At</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @isset($gradingSystems)
                @foreach ($gradingSystems as $gradingSystem)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-4 py-3 text-gray-600">{{$gradingSystem->system_name}}</td>
                    <td class="px-4 py-3 text-gray-600">{{$gradingSystem->schoolClass->class_name}}</td>
                    <td class="px-4 py-3 text-gray-600">{{$gradingSystem->semester->semester_name}}</td>
                    <td class="px-4 py-3 text-gray-600">{{$gradingSystem->created_at}}</td>
                    <td class="px-4 py-3">
                        <div class="flex gap-2">
                            <a href="{{route('exam.grade.system.rule.create', ['grading_system_id' => $gradingSystem->id])}}" class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 transition-colors border border-gray-200">
                                <i data-lucide="plus" class="w-3.5 h-3.5"></i> Add Rule
                            </a>
                            <a href="{{route('exam.grade.system.rule.show', ['grading_system_id' => $gradingSystem->id])}}" class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 transition-colors border border-gray-200">
                                <i data-lucide="eye" class="w-3.5 h-3.5"></i> View Rules
                            </a>
                        </div>
                    </td>
                </tr>
                @endforeach
            @endisset
        </tbody>
    </table>
</div>
@endsection
