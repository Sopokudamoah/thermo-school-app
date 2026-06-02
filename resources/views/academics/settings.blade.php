@extends('layouts.app')

@section('page-title', 'System Settings')

@section('content')

<div class="mb-6">
    <h1 class="font-heading text-xl font-bold text-gray-900">
        <i data-lucide="settings" class="w-5 h-5 inline-block mr-1.5 align-text-bottom"></i> System Settings
    </h1>
    <nav class="flex items-center gap-1.5 mt-1 text-sm text-gray-500">
        <a href="{{ route('home') }}" class="hover:text-indigo-600 transition-colors">Home</a>
        <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
        <span class="text-gray-900">System Settings</span>
    </nav>
</div>

@include('session-messages')

<div class="mb-4">
    <div class="columns-1 md:columns-2 xl:columns-3 gap-4">

        <div class="break-inside-avoid bg-white rounded-card shadow-card border border-gray-200 p-5 mb-4">
            <h3 class="font-heading text-sm font-semibold text-gray-700 uppercase tracking-wider mb-4 pb-2 border-b border-gray-100">
                General School Settings</h3>
            <form action="{{route('school.general.settings.update')}}" method="POST" enctype="multipart/form-data"
                  class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">School Name <sup
                            class="text-indigo-500">*</sup></label>
                    <input type="text"
                           class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                           name="school_name" value="{{ $academic_setting->school_name }}" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">School Logo</label>
                    @if($academic_setting->logo)
                        <div class="mb-2">
                            <img src="{{ asset($academic_setting->logo) }}" alt="Logo"
                                 class="h-12 w-auto object-contain">
                        </div>
                    @endif
                    <input type="file"
                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                           name="logo">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">School Address</label>
                    <textarea
                        class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                        name="school_address" rows="2">{{ $academic_setting->school_address }}</textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Phone</label>
                        <input type="text"
                               class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                               name="school_phone" value="{{ $academic_setting->school_phone }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                        <input type="email"
                               class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                               name="school_email" value="{{ $academic_setting->school_email }}">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Currency Symbol <sup
                                class="text-indigo-500">*</sup></label>
                        <input type="text"
                               class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                               name="currency_symbol" value="{{ $academic_setting->currency_symbol }}" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Currency Code <sup
                                class="text-indigo-500">*</sup></label>
                        <input type="text"
                               class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                               name="currency_code" value="{{ $academic_setting->currency_code }}" required>
                    </div>
                </div>
                <button
                    class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm px-4 py-2.5 rounded-lg transition-colors"
                    type="submit">
                    <i data-lucide="save" class="w-4 h-4"></i> Save General Settings
                </button>
            </form>
        </div>

    @if ($latest_school_session_id == $current_school_session_id)
        <div class="break-inside-avoid bg-white rounded-card shadow-card border border-gray-200 p-5 mb-4">
            <h3 class="font-heading text-sm font-semibold text-gray-700 uppercase tracking-wider mb-4 pb-2 border-b border-gray-100">Create Session</h3>
            <div class="flex items-start gap-2.5 bg-indigo-50 border border-indigo-200 text-indigo-800 rounded-lg px-4 py-3 mb-4 text-sm">
                <i data-lucide="alert-circle" class="w-4 h-4 shrink-0 mt-0.5"></i>
                <span>Create one Session per academic year. Last created session will be considered as the latest academic session.</span>
            </div>
            <form action="{{route('school.session.store')}}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Session Name <sup
                            class="text-indigo-500">*</sup></label>
                    <input type="text"
                           class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                           placeholder="2021 - 2022" aria-label="Current Session" name="session_name" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Starts <sup
                            class="text-indigo-500">*</sup></label>
                    <input type="date"
                           class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                           name="start_date" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Ends <sup
                            class="text-indigo-500">*</sup></label>
                    <input type="date"
                           class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                           name="end_date" required>
                </div>
                <button class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm px-4 py-2.5 rounded-lg transition-colors" type="submit">
                    <i data-lucide="check" class="w-4 h-4"></i> Create
                </button>
            </form>
        </div>
        @endif

        <div class="break-inside-avoid bg-white rounded-card shadow-card border border-gray-200 p-5 mb-4">
            <h3 class="font-heading text-sm font-semibold text-gray-700 uppercase tracking-wider mb-4 pb-2 border-b border-gray-100">Browse by Session</h3>
            <div class="flex items-start gap-2.5 bg-indigo-50 border border-indigo-200 text-indigo-800 rounded-lg px-4 py-3 mb-4 text-sm">
                <i data-lucide="alert-circle" class="w-4 h-4 shrink-0 mt-0.5"></i>
                <span>Only use this when you want to browse data from previous Sessions.</span>
            </div>
            <form action="{{route('school.session.browse')}}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Select "Session" to browse by:</label>
                    <select class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 bg-white focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                            name="session_id" required>
                        @isset($school_sessions)
                            @foreach ($school_sessions as $school_session)
                                <option value="{{$school_session->id}}">{{$school_session->session_name}}</option>
                            @endforeach
                        @endisset
                    </select>
                </div>
                <button class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm px-4 py-2.5 rounded-lg transition-colors" type="submit">
                    <i data-lucide="check" class="w-4 h-4"></i> Set
                </button>
            </form>
        </div>

            <div class="break-inside-avoid bg-white rounded-card shadow-card border border-gray-200 p-5 mb-4">
                <h3 class="font-heading text-sm font-semibold text-gray-700 uppercase tracking-wider mb-4 pb-2 border-b border-gray-100">
                    Set Active Session</h3>
                <div
                    class="flex items-start gap-2.5 bg-indigo-50 border border-indigo-200 text-indigo-800 rounded-lg px-4 py-3 mb-4 text-sm">
                    <i data-lucide="alert-circle" class="w-4 h-4 shrink-0 mt-0.5"></i>
                    <span>Setting an active session will make it the default session for all users.</span>
                </div>
                <form action="{{route('school.active.session.update')}}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Select Active Session:</label>
                        <select
                            class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 bg-white focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                            name="active_session_id" required>
                            <option value="" disabled {{!$academic_setting->active_session_id ? 'selected' : ''}}>
                                Select Session
                            </option>
                            @isset($school_sessions)
                                @foreach ($school_sessions as $school_session)
                                    <option
                                        value="{{$school_session->id}}" {{$academic_setting->active_session_id == $school_session->id ? 'selected' : ''}}>{{$school_session->session_name}}</option>
                                @endforeach
                            @endisset
                        </select>
                    </div>
                    <button
                        class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm px-4 py-2.5 rounded-lg transition-colors"
                        type="submit">
                        <i data-lucide="check" class="w-4 h-4"></i> Set Active
                    </button>
                </form>
            </div>

            <div class="break-inside-avoid bg-white rounded-card shadow-card border border-gray-200 p-5 mb-4">
                <h3 class="font-heading text-sm font-semibold text-gray-700 uppercase tracking-wider mb-4 pb-2 border-b border-gray-100">
                    Set Active Semester</h3>
                <div
                    class="flex items-start gap-2.5 bg-indigo-50 border border-indigo-200 text-indigo-800 rounded-lg px-4 py-3 mb-4 text-sm">
                    <i data-lucide="alert-circle" class="w-4 h-4 shrink-0 mt-0.5"></i>
                    <span>Setting an active semester will pre-select it in all related forms.</span>
                </div>
                <form action="{{route('school.active.semester.update')}}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Select Active Semester:</label>
                        <select
                            class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 bg-white focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                            name="active_semester_id" required>
                            <option value="" disabled {{!$academic_setting->active_semester_id ? 'selected' : ''}}>
                                Select Semester
                            </option>
                            @isset($semesters)
                                @foreach ($semesters as $semester)
                                    <option
                                        value="{{$semester->id}}" {{$academic_setting->active_semester_id == $semester->id ? 'selected' : ''}}>{{$semester->semester_name}}</option>
                                @endforeach
                            @endisset
                        </select>
                    </div>
                    <button
                        class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm px-4 py-2.5 rounded-lg transition-colors"
                        type="submit">
                        <i data-lucide="check" class="w-4 h-4"></i> Set Active
                    </button>
                </form>
            </div>

            @if ($latest_school_session_id == $current_school_session_id)
        <div class="break-inside-avoid bg-white rounded-card shadow-card border border-gray-200 p-5 mb-4">
            <h3 class="font-heading text-sm font-semibold text-gray-700 uppercase tracking-wider mb-4 pb-2 border-b border-gray-100">Create Semester for Current Session</h3>
            <form action="{{route('school.semester.create')}}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="session_id" value="{{$current_school_session_id}}">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Semester name <sup class="text-indigo-500">*</sup></label>
                    <input type="text"
                           class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                           placeholder="First Semester" aria-label="Semester name" name="semester_name" required>
                </div>
                <div>
                    <label for="inputStarts" class="block text-sm font-medium text-gray-700 mb-1.5">Starts <sup class="text-indigo-500">*</sup></label>
                    <input type="date"
                           class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                           id="inputStarts" name="start_date" required>
                </div>
                <div>
                    <label for="inputEnds" class="block text-sm font-medium text-gray-700 mb-1.5">Ends <sup class="text-indigo-500">*</sup></label>
                    <input type="date"
                           class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                           id="inputEnds" name="end_date" required>
                </div>
                <button type="submit" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm px-4 py-2.5 rounded-lg transition-colors">
                    <i data-lucide="check" class="w-4 h-4"></i> Create
                </button>
            </form>
        </div>

        <div class="break-inside-avoid bg-white rounded-card shadow-card border border-gray-200 p-5 mb-4">
            <h3 class="font-heading text-sm font-semibold text-gray-700 uppercase tracking-wider mb-4 pb-2 border-b border-gray-100">Attendance Type</h3>
            <div class="flex items-start gap-2.5 bg-indigo-50 border border-indigo-200 text-indigo-800 rounded-lg px-4 py-3 mb-4 text-sm">
                <i data-lucide="alert-circle" class="w-4 h-4 shrink-0 mt-0.5"></i>
                <span>Do not change the type in the middle of a Semester.</span>
            </div>
            <form action="{{route('school.attendance.type.update')}}" method="POST" class="space-y-3">
                @csrf
                <label class="flex items-center gap-2.5 cursor-pointer">
                    <input class="w-4 h-4 rounded-full border-gray-300 text-indigo-600 focus:ring-indigo-500"
                           type="radio" name="attendance_type" id="attendance_type_section"
                           {{($academic_setting->attendance_type == 'section')?'checked="checked"':null}} value="section">
                    <span class="text-sm text-gray-700">Attendance by Section</span>
                </label>
                <label class="flex items-center gap-2.5 cursor-pointer">
                    <input class="w-4 h-4 rounded-full border-gray-300 text-indigo-600 focus:ring-indigo-500"
                           type="radio" name="attendance_type" id="attendance_type_course"
                           {{($academic_setting->attendance_type == 'course')?'checked="checked"':null}} value="course">
                    <span class="text-sm text-gray-700">Attendance by Course</span>
                </label>
                <div class="pt-1">
                    <button type="submit" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm px-4 py-2.5 rounded-lg transition-colors">
                        <i data-lucide="check" class="w-4 h-4"></i> Save
                    </button>
                </div>
            </form>
        </div>

        <div class="break-inside-avoid bg-white rounded-card shadow-card border border-gray-200 p-5 mb-4">
            <h3 class="font-heading text-sm font-semibold text-gray-700 uppercase tracking-wider mb-4 pb-2 border-b border-gray-100">Create Class</h3>
            <form action="{{route('school.class.create')}}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="session_id" value="{{$current_school_session_id}}">
                <div>
                    <input type="text"
                           class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                           name="class_name" placeholder="Class name" aria-label="Class name" required>
                </div>
                <button class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm px-4 py-2.5 rounded-lg transition-colors" type="submit">
                    <i data-lucide="check" class="w-4 h-4"></i> Create
                </button>
            </form>
        </div>

        <div class="break-inside-avoid bg-white rounded-card shadow-card border border-gray-200 p-5 mb-4">
            <h3 class="font-heading text-sm font-semibold text-gray-700 uppercase tracking-wider mb-4 pb-2 border-b border-gray-100">Create Section</h3>
            <form action="{{route('school.section.create')}}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="session_id" value="{{$current_school_session_id}}">
                <div>
                    <input class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                           name="section_name" type="text" placeholder="Section name" required>
                </div>
                <div>
                    <input class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                           name="room_no" type="text" placeholder="Room No." required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Assign section to class:</label>
                    <select class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 bg-white focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                            name="class_id" required>
                        @isset($school_classes)
                            @foreach ($school_classes as $school_class)
                            <option value="{{$school_class->id}}">{{$school_class->class_name}}</option>
                            @endforeach
                        @endisset
                    </select>
                </div>
                <button type="submit" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm px-4 py-2.5 rounded-lg transition-colors">
                    <i data-lucide="check" class="w-4 h-4"></i> Save
                </button>
            </form>
        </div>

        <div class="break-inside-avoid bg-white rounded-card shadow-card border border-gray-200 p-5 mb-4">
            <h3 class="font-heading text-sm font-semibold text-gray-700 uppercase tracking-wider mb-4 pb-2 border-b border-gray-100">Create Course</h3>
            <form action="{{route('school.course.create')}}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="session_id" value="{{$current_school_session_id}}">
                <div>
                    <input type="text"
                           class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                           name="course_name" placeholder="Course name" aria-label="Course name" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Course Type: <sup class="text-indigo-500">*</sup></label>
                    <select class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 bg-white focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                            name="course_type" required>
                        <option value="Core">Core</option>
                        <option value="General">General</option>
                        <option value="Elective">Elective</option>
                        <option value="Optional">Optional</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Assign to semester: <sup class="text-indigo-500">*</sup></label>
                    <select class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 bg-white focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                            name="semester_id" required>
                        @isset($semesters)
                            @foreach ($semesters as $semester)
                                <option
                                    value="{{$semester->id}}" {{$academic_setting->active_semester_id == $semester->id ? 'selected' : ''}}>{{$semester->semester_name}}</option>
                            @endforeach
                        @endisset
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Assign to class: <sup class="text-indigo-500">*</sup></label>
                    <select class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 bg-white focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                            name="class_id" required>
                        @isset($school_classes)
                            @foreach ($school_classes as $school_class)
                            <option value="{{$school_class->id}}">{{$school_class->class_name}}</option>
                            @endforeach
                        @endisset
                    </select>
                </div>
                <button class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm px-4 py-2.5 rounded-lg transition-colors" type="submit">
                    <i data-lucide="check" class="w-4 h-4"></i> Create
                </button>
            </form>
        </div>

        <div class="break-inside-avoid bg-white rounded-card shadow-card border border-gray-200 p-5 mb-4">
            <h3 class="font-heading text-sm font-semibold text-gray-700 uppercase tracking-wider mb-4 pb-2 border-b border-gray-100">Assign Teacher</h3>
            <form action="{{route('school.teacher.assign')}}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="session_id" value="{{$current_school_session_id}}">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Select Teacher: <sup class="text-indigo-500">*</sup></label>
                    <select class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 bg-white focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                            name="teacher_id" required>
                        @isset($teachers)
                            @foreach ($teachers as $teacher)
                            <option value="{{$teacher->id}}">{{$teacher->first_name}} {{$teacher->last_name}}</option>
                            @endforeach
                        @endisset
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Assign to semester: <sup class="text-indigo-500">*</sup></label>
                    <select class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 bg-white focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                            name="semester_id" required>
                        @isset($semesters)
                            @foreach ($semesters as $semester)
                                <option
                                    value="{{$semester->id}}" {{$academic_setting->active_semester_id == $semester->id ? 'selected' : ''}}>{{$semester->semester_name}}</option>
                            @endforeach
                        @endisset
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Assign to class: <sup class="text-indigo-500">*</sup></label>
                    <select onchange="getSectionsAndCourses(this);"
                            class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 bg-white focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                            name="class_id" required>
                        @isset($school_classes)
                            <option selected disabled>Please select a class</option>
                            @foreach ($school_classes as $school_class)
                            <option value="{{$school_class->id}}">{{$school_class->class_name}}</option>
                            @endforeach
                        @endisset
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Assign to section: <sup class="text-indigo-500">*</sup></label>
                    <select class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 bg-white focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                            id="section-select" name="section_id" required>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Assign to course: <sup class="text-indigo-500">*</sup></label>
                    <select class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 bg-white focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                            id="course-select" name="course_id" required>
                    </select>
                </div>
                <button type="submit" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm px-4 py-2.5 rounded-lg transition-colors">
                    <i data-lucide="check" class="w-4 h-4"></i> Save
                </button>
            </form>
        </div>

        <div class="break-inside-avoid bg-white rounded-card shadow-card border border-gray-200 p-5 mb-4">
            <h3 class="font-heading text-sm font-semibold text-gray-700 uppercase tracking-wider mb-4 pb-2 border-b border-gray-100">Allow Final Marks Submission</h3>
            <form action="{{route('school.final.marks.submission.status.update')}}" method="POST" class="space-y-4">
                @csrf
                <div class="flex items-start gap-2.5 bg-indigo-50 border border-indigo-200 text-indigo-800 rounded-lg px-4 py-3 text-sm">
                    <i data-lucide="alert-circle" class="w-4 h-4 shrink-0 mt-0.5"></i>
                    <span>Usually teachers are allowed to submit final marks just before the end of a "Semester". Disallow at the start of a "Semester".</span>
                </div>
                <label class="flex items-center gap-2.5 cursor-pointer">
                    <div class="relative inline-flex">
                        <input class="sr-only peer" type="checkbox" name="marks_submission_status" id="marks_submission_status_check"
                               {{($academic_setting->marks_submission_status == 'on')?'checked="checked"':null}}>
                        <div class="w-10 h-6 bg-gray-200 rounded-full peer peer-checked:bg-indigo-600 peer-focus:ring-2 peer-focus:ring-indigo-300 transition-colors"></div>
                        <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full shadow transition-transform peer-checked:translate-x-4"></div>
                    </div>
                    <span class="text-sm text-gray-700" id="marks_submission_label">
                        {{($academic_setting->marks_submission_status == 'on')?'Allowed':'Disallowed'}}
                    </span>
                </label>
                <div>
                    <button type="submit" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm px-4 py-2.5 rounded-lg transition-colors">
                        <i data-lucide="check" class="w-4 h-4"></i> Save
                    </button>
                </div>
            </form>
        </div>
        @endif

    </div>
</div>

@endsection

@push('scripts')
<script>
    function getSectionsAndCourses(obj) {
        var class_id = obj.options[obj.selectedIndex].value;

        var url = "{{route('get.sections.courses.by.classId')}}?class_id=" + class_id

        fetch(url)
        .then((resp) => resp.json())
        .then(function(data) {
            var sectionSelect = document.getElementById('section-select');
            sectionSelect.options.length = 0;
            data.sections.unshift({'id': 0,'section_name': 'Please select a section'})
            data.sections.forEach(function(section, key) {
                sectionSelect[key] = new Option(section.section_name, section.id);
            });

            var courseSelect = document.getElementById('course-select');
            courseSelect.options.length = 0;
            data.courses.unshift({'id': 0,'course_name': 'Please select a course'})
            data.courses.forEach(function(course, key) {
                courseSelect[key] = new Option(course.course_name, course.id);
            });
        })
        .catch(function(error) {
            console.log(error);
        });
    }
</script>
@endpush
