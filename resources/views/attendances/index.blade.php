@extends('layouts.app')
@section('page-title', 'Attendance')

@section('content')
<div class="mb-6">
    <h1 class="font-heading text-xl font-bold text-gray-900"><i data-lucide="calendar-check" class="inline w-5 h-5 mr-2"></i> Attendance</h1>
    <nav class="flex items-center gap-1.5 mt-1 text-sm text-gray-500">
        <a href="{{route('home')}}" class="hover:text-indigo-600">Home</a>
        <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
        <span>Attendance</span>
    </nav>
</div>

<div class="space-y-4">
    @foreach ($classes_and_sections['school_classes'] as $school_class)
    <div class="bg-white rounded-card shadow-card border border-gray-200">
        <div class="px-4 py-3 border-b border-gray-200 font-semibold text-gray-700 text-sm">
            {{$school_class->class_name}}
        </div>
        <div class="p-4">
            @if ($academic_setting->attendance_type == 'course')
                @foreach ($courses as $course)
                    @if ($course->class_id == $school_class->id)
                    <div class="mb-4">
                        <p class="text-sm font-medium text-gray-700 mb-2">Course: {{$course->course_name}}</p>
                        <div class="flex gap-2">
                            <a href="{{url('attendances/view?class_id='.$school_class->id.'&class_name='.$school_class->class_name.'&course_id='.$course->id.'&course_name='.$course->course_name)}}"
                               class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 transition-colors border border-gray-200">
                                <i data-lucide="eye" class="w-3.5 h-3.5"></i> View Attendance
                            </a>
                            <a href="{{url('attendances/take?class_id='.$school_class->id.'&class_name='.$school_class->class_name.'&course_id='.$course->id.'&course_name='.$course->course_name)}}"
                               class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 transition-colors border border-gray-200">
                                <i data-lucide="calendar-check" class="w-3.5 h-3.5"></i> Take Attendance
                            </a>
                        </div>
                    </div>
                    @endif
                @endforeach
            @else
            <div class="space-y-2">
                @foreach ($classes_and_sections['school_sections'] as $school_section)
                <div x-data="{ open: false }" class="border border-gray-200 rounded-lg">
                    <button @click="open = !open" type="button"
                        class="w-full flex items-center justify-between px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 rounded-lg transition-colors">
                        <span>{{$school_section->section_name}}</span>
                        <i data-lucide="chevron-right" class="w-4 h-4 transition-transform" :class="open ? 'rotate-90' : ''"></i>
                    </button>
                    <div x-show="open" x-cloak class="border-t border-gray-200 px-4 py-3 flex gap-2">
                        <a href="{{url('attendances/view?class_id='.$school_class->id.'&section_id='.$school_section->id.'&class_name='.$school_class->class_name.'&section_name='.$school_section->section_name)}}"
                           class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 transition-colors border border-gray-200">
                            <i data-lucide="eye" class="w-3.5 h-3.5"></i> View Attendance
                        </a>
                        <a href="{{url('attendances/take?class_id='.$school_class->id.'&class_name='.$school_class->class_name.'&section_id='.$school_section->id.'&section_name='.$school_section->section_name)}}"
                           class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 transition-colors border border-gray-200">
                            <i data-lucide="calendar-check" class="w-3.5 h-3.5"></i> Take Attendance
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>
    @endforeach
</div>
@endsection
