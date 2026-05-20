@extends('layouts.app')
@section('page-title', 'Promote Class Section')

@section('content')
<div class="mb-6">
    <h1 class="font-heading text-xl font-bold text-gray-900"><i data-lucide="arrow-up-01" class="inline w-5 h-5 mr-2"></i> Promote Class Section</h1>
</div>

<div class="bg-white rounded-card shadow-card border border-gray-200 p-4 mb-5">
    <p class="text-sm font-medium text-gray-600 mb-3">Filter list by:</p>
    <form action="{{route('promotions.index')}}" method="GET">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <div>
                <select class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 bg-white" name="class_id" required>
                    @isset($previousSessionClasses)
                        <option selected disabled>Please select a class</option>
                        @foreach ($previousSessionClasses as $school_class)
                        <option value="{{$school_class->schoolClass->id}}">{{$school_class->schoolClass->class_name}}</option>
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
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Section Name</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Promotion Status</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @isset($previousSessionSections)
                @foreach ($previousSessionSections as $previousSessionSection)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-4 py-3 text-gray-600">{{$previousSessionSection->section->section_name}}</td>
                    <td class="px-4 py-3">
                        @if ($currentSessionSectionsCounts > 0)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">Promoted</span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">Not Promoted</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        @if ($currentSessionSectionsCounts > 0)
                            <span class="text-sm text-gray-400">No action needed</span>
                        @else
                            <a href="{{route('promotions.create', ['previousSessionId' => $previousSessionId,'previous_section_id' => $previousSessionSection->section->id, 'previous_class_id' => $class_id])}}" class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 transition-colors border border-gray-200">
                                <i data-lucide="arrow-up-01" class="w-3.5 h-3.5"></i> Promote
                            </a>
                        @endif
                    </td>
                </tr>
                @endforeach
            @endisset
        </tbody>
    </table>
</div>
@endsection
