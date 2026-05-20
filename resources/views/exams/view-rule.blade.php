@extends('layouts.app')
@section('page-title', 'Exam Rules')

@section('content')
<div class="mb-6">
    <h1 class="font-heading text-xl font-bold text-gray-900"><i data-lucide="file-text" class="inline w-5 h-5 mr-2"></i> Exam Rules</h1>
    <nav class="flex items-center gap-1.5 mt-1 text-sm text-gray-500">
        <a href="{{route('home')}}" class="hover:text-indigo-600">Home</a>
        <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
        <a href="{{url()->previous()}}" class="hover:text-indigo-600">Exams</a>
        <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
        <span>Exam Rules</span>
    </nav>
</div>

<div class="bg-white rounded-card shadow-card border border-gray-200">
    <table class="w-full text-sm">
        <thead>
            <tr class="bg-gray-50 border-b border-gray-200">
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Marks</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Pass Marks</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Marks Distribution Note</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @foreach ($exam_rules as $exam_rule)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-4 py-3 text-gray-600">{{$exam_rule->total_marks}}</td>
                <td class="px-4 py-3 text-gray-600">{{$exam_rule->pass_marks}}</td>
                <td class="px-4 py-3 text-gray-600">{{$exam_rule->marks_distribution_note}}</td>
                <td class="px-4 py-3">
                    <a href="{{route('exam.rule.edit', ['exam_rule_id' => $exam_rule->id])}}" class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 transition-colors border border-gray-200">
                        <i data-lucide="pencil" class="w-3.5 h-3.5"></i> Edit
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
