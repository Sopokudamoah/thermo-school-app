@extends('layouts.app')
@section('page-title', 'Exam History')

@section('content')
<div class="mb-6">
    <h1 class="font-heading text-xl font-bold text-gray-900"><i data-lucide="file-text" class="inline w-5 h-5 mr-2"></i> Exam History</h1>
</div>

<div class="space-y-4">
    <div class="bg-white rounded-card shadow-card border border-gray-200">
        <div class="px-4 py-3 border-b border-gray-200 font-semibold text-gray-700 text-sm">
            Class 1
        </div>
        <div class="p-4">
            <div class="space-y-2">
                <div x-data="{ open: false }" class="border border-gray-200 rounded-lg">
                    <button @click="open = !open" type="button"
                        class="w-full flex items-center justify-between px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 rounded-lg transition-colors">
                        <span>Section #1</span>
                        <i data-lucide="chevron-right" class="w-4 h-4 transition-transform" :class="open ? 'rotate-90' : ''"></i>
                    </button>
                    <div x-show="open" x-cloak class="border-t border-gray-200 p-4 space-y-3">
                        <div class="bg-white border border-gray-200 rounded-lg p-4">
                            <div class="flex items-start justify-between mb-1">
                                <span class="text-base font-semibold text-gray-800">Quiz 1</span>
                                <span class="text-sm text-gray-500">Jan 9th 2021 9:00 AM</span>
                            </div>
                            <div class="flex items-center justify-between text-sm text-gray-500 mb-2">
                                <span>Belongs to: First Semester</span>
                                <span>Starts: Jan 9th 2021 - Ends: Jan 15th 2021</span>
                            </div>
                            <div class="flex gap-2 flex-wrap">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700">Course: Math</span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-800 text-white">Marks: 100</span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-700">Category: Quiz</span>
                            </div>
                        </div>
                        <div class="bg-white border border-gray-200 rounded-lg p-4">
                            <div class="flex items-start justify-between mb-1">
                                <span class="text-base font-semibold text-gray-800">Day 2 Sessions</span>
                                <span class="text-sm text-gray-500">Tue, Jan 10th 2019 8:30 AM</span>
                            </div>
                            <p class="text-sm text-gray-600">Sign-up for the lessons and speakers that coincide with your course syllabus. Meet and greet with instructors.</p>
                        </div>
                        <div class="bg-white border border-gray-200 rounded-lg p-4">
                            <div class="flex items-start justify-between mb-1">
                                <span class="text-base font-semibold text-gray-800">Day 1 Orientation</span>
                                <span class="text-sm text-gray-500">Mon, Jan 9th 2019 7:00 AM</span>
                            </div>
                            <p class="text-sm text-gray-600">Welcome to the campus, introduction and get started with the tour.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
