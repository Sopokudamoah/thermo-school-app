@extends('layouts.app')
@section('page-title', 'View Grading Rule')

@section('content')
<div class="mb-6">
    <h1 class="font-heading text-xl font-bold text-gray-900"><i data-lucide="file-text" class="inline w-5 h-5 mr-2"></i> View Grading Rule</h1>
</div>

@include('session-messages')

<div class="bg-white rounded-card shadow-card border border-gray-200">
    <table class="w-full text-sm">
        <thead>
            <tr class="bg-gray-50 border-b border-gray-200">
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">System Name</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Points</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Grade</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Starts At</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Ends At</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @isset($gradeRules)
                @foreach ($gradeRules as $gradeRule)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-4 py-3 text-gray-600">{{$gradeRule->gradingSystem->system_name}}</td>
                    <td class="px-4 py-3 text-gray-600">{{$gradeRule->point}}</td>
                    <td class="px-4 py-3 text-gray-600">{{$gradeRule->grade}}</td>
                    <td class="px-4 py-3 text-gray-600">{{$gradeRule->start_at}}</td>
                    <td class="px-4 py-3 text-gray-600">{{$gradeRule->end_at}}</td>
                    <td class="px-4 py-3">
                        <a href="{{route('exam.grade.system.rule.delete')}}" class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-medium text-red-600 hover:bg-red-50 transition-colors border border-red-200"
                           onclick="event.preventDefault(); document.getElementById('delete-form-{{$gradeRule->id}}').submit();">
                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i> Delete
                        </a>
                        <form id="delete-form-{{$gradeRule->id}}" action="{{ route('exam.grade.system.rule.delete') }}" method="POST" class="hidden">
                            @csrf
                            <input type="hidden" name="id" value="{{$gradeRule->id}}">
                        </form>
                    </td>
                </tr>
                @endforeach
            @endisset
        </tbody>
    </table>
</div>
@endsection
