@extends('layouts.app')
@section('page-title', 'Edit Exam Rule')

@section('content')
<div class="mb-6">
    <h1 class="font-heading text-xl font-bold text-gray-900"><i data-lucide="file-plus" class="inline w-5 h-5 mr-2"></i> Edit Exam Rule</h1>
    <nav class="flex items-center gap-1.5 mt-1 text-sm text-gray-500">
        <a href="{{route('home')}}" class="hover:text-indigo-600">Home</a>
        <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
        <a href="{{url()->previous()}}" class="hover:text-indigo-600">Exams Rules</a>
        <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
        <span>Edit Exam Rule</span>
    </nav>
</div>

@include('session-messages')

<div class="bg-white rounded-card shadow-card border border-gray-200 p-6 max-w-lg">
    <form action="{{route('exam.rule.update')}}" method="POST">
        @csrf
        <input type="hidden" name="exam_rule_id" value="{{$exam_rule_id}}">
        <div class="mb-4">
            <label for="inputTotalMarks" class="block text-sm font-medium text-gray-700 mb-1.5">Total Marks <sup class="text-indigo-500">*</sup></label>
            <input type="number" class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100" id="inputTotalMarks" value="{{$exam_rule->total_marks}}" name="total_marks" step="0.01">
        </div>
        <div class="mb-4">
            <label for="inputPassMarks" class="block text-sm font-medium text-gray-700 mb-1.5">Pass Marks <sup class="text-indigo-500">*</sup></label>
            <input type="number" class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100" id="inputPassMarks" value="{{$exam_rule->pass_marks}}" name="pass_marks" step="0.01">
        </div>
        <div class="mb-6">
            <label for="inputMarksDistributionNote" class="block text-sm font-medium text-gray-700 mb-1.5">Marks Distribution Note <sup class="text-indigo-500">*</sup></label>
            <textarea class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100" id="inputMarksDistributionNote" rows="3" name="marks_distribution_note">{{$exam_rule->marks_distribution_note}}</textarea>
        </div>
        <button type="submit" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm px-4 py-2.5 rounded-lg transition-colors">
            <i data-lucide="check" class="w-4 h-4"></i> Save
        </button>
    </form>
</div>
@endsection
