<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
       class="fixed left-0 top-0 h-full w-[260px] bg-white border-r border-gray-200 z-50 flex flex-col transition-transform duration-300 overflow-y-auto">

    {{-- Logo --}}
    <div class="h-16 flex items-center px-5 border-b border-gray-200 shrink-0">
        <a href="{{ url('/') }}" class="flex items-center gap-2.5 hover:opacity-80 transition-opacity">
            <img src="{{ asset('imgs/logo.png') }}" alt="{{ config('app.name', 'Unifiedtransform') }}" class="h-8 w-auto object-contain">
            <span class="font-heading font-semibold text-base text-indigo-600 truncate">{{ config('app.name', 'Unifiedtransform') }}</span>
        </a>
    </div>

    {{-- User identity --}}
    <div class="px-4 py-3 border-b border-gray-100 shrink-0">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-full bg-indigo-100 flex items-center justify-center shrink-0 text-indigo-600 font-semibold text-sm">
                {{ strtoupper(substr(Auth::user()->first_name, 0, 1)) }}
            </div>
            <div class="min-w-0">
                <p class="text-sm font-semibold text-gray-900 truncate">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</p>
                <span class="inline-block text-xs font-medium text-indigo-600 bg-indigo-50 rounded-full px-2 py-0.5 capitalize mt-0.5">{{ Auth::user()->role }}</span>
            </div>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 px-3 py-3 space-y-0.5 text-sm">

        {{-- Dashboard --}}
        <a href="{{ url('home') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->is('home') ? 'sidebar-link-active' : 'text-gray-700 hover:bg-gray-50' }}">
            <i data-lucide="layout-dashboard" class="w-4 h-4 shrink-0 {{ request()->is('home') ? 'text-indigo-600' : 'text-gray-400' }}"></i>
            <span>{{ __('Dashboard') }}</span>
        </a>

        {{-- ACADEMIC group label --}}
        <p class="px-3 pt-4 pb-1 text-xs font-semibold text-gray-400 uppercase tracking-wider">Academic</p>

        {{-- Classes --}}
        @can('view classes')
        @php
            if (session()->has('browse_session_id')) {
                $classCount = \App\Models\SchoolClass::where('session_id', session('browse_session_id'))->count();
            } else {
                $latest_session = \App\Models\SchoolSession::latest()->first();
                $classCount = $latest_session ? \App\Models\SchoolClass::where('session_id', $latest_session->id)->count() : 0;
            }
        @endphp
        <a href="{{ url('classes') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->is('classes') ? 'sidebar-link-active' : 'text-gray-700 hover:bg-gray-50' }}">
            <i data-lucide="git-branch" class="w-4 h-4 shrink-0 {{ request()->is('classes') ? 'text-indigo-600' : 'text-gray-400' }}"></i>
            <span class="flex-1">Classes</span>
            <span class="text-xs font-medium text-gray-400 bg-gray-100 rounded-full px-1.5 py-0.5">{{ $classCount }}</span>
        </a>
        @endcan

        {{-- Students submenu (admin/teacher only) --}}
        @if(Auth::user()->role != "student")
        <div x-data="{ open: {{ request()->is('students*') ? 'true' : 'false' }} }">
            <button @click="open = !open"
                    class="w-full flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->is('students*') ? 'sidebar-link-active' : 'text-gray-700 hover:bg-gray-50' }}">
                <i data-lucide="users" class="w-4 h-4 shrink-0 {{ request()->is('students*') ? 'text-indigo-600' : 'text-gray-400' }}"></i>
                <span class="flex-1 text-left">Students</span>
                <i data-lucide="chevron-down" class="w-3.5 h-3.5 text-gray-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''"></i>
            </button>
            <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="sidebar-submenu mt-0.5 space-y-0.5">
                <a href="{{ route('student.list.show') }}"
                   class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('student.list.show') ? 'sidebar-link-active' : 'text-gray-600 hover:bg-gray-50' }}">
                    <i data-lucide="user-search" class="w-3.5 h-3.5 shrink-0 text-gray-400"></i>
                    View Students
                </a>
                @if (!session()->has('browse_session_id') && Auth::user()->role == "admin")
                <a href="{{ route('student.create.show') }}"
                   class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('student.create.show') ? 'sidebar-link-active' : 'text-gray-600 hover:bg-gray-50' }}">
                    <i data-lucide="user-plus" class="w-3.5 h-3.5 shrink-0 text-gray-400"></i>
                    Add Student
                </a>
                @endif
            </div>
        </div>

        {{-- Teachers submenu --}}
        <div x-data="{ open: {{ request()->is('teachers*') ? 'true' : 'false' }} }">
            <button @click="open = !open"
                    class="w-full flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->is('teachers*') ? 'sidebar-link-active' : 'text-gray-700 hover:bg-gray-50' }}">
                <i data-lucide="user-check" class="w-4 h-4 shrink-0 {{ request()->is('teachers*') ? 'text-indigo-600' : 'text-gray-400' }}"></i>
                <span class="flex-1 text-left">Teachers</span>
                <i data-lucide="chevron-down" class="w-3.5 h-3.5 text-gray-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''"></i>
            </button>
            <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="sidebar-submenu mt-0.5 space-y-0.5">
                <a href="{{ route('teacher.list.show') }}"
                   class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('teacher.list.show') ? 'sidebar-link-active' : 'text-gray-600 hover:bg-gray-50' }}">
                    <i data-lucide="user-search" class="w-3.5 h-3.5 shrink-0 text-gray-400"></i>
                    View Teachers
                </a>
                @if (!session()->has('browse_session_id') && Auth::user()->role == "admin")
                <a href="{{ route('teacher.create.show') }}"
                   class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('teacher.create.show') ? 'sidebar-link-active' : 'text-gray-600 hover:bg-gray-50' }}">
                    <i data-lucide="user-plus" class="w-3.5 h-3.5 shrink-0 text-gray-400"></i>
                    Add Teacher
                </a>
                @endif
            </div>
        </div>
        @endif

        {{-- My Courses (teacher only) --}}
        @if(Auth::user()->role == "teacher")
        <a href="{{ route('course.teacher.list.show', ['teacher_id' => Auth::user()->id]) }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ (request()->is('courses/teacher*') || request()->is('courses/assignments*')) ? 'sidebar-link-active' : 'text-gray-700 hover:bg-gray-50' }}">
            <i data-lucide="book-open" class="w-4 h-4 shrink-0{{ (request()->is('courses/teacher*') || request()->is('courses/assignments*')) ? ' text-indigo-600' : ' text-gray-400' }}"></i>
            <span>My Courses</span>
        </a>
        @endif

        {{-- Student-only nav items --}}
        @if(Auth::user()->role == "student")
        <a href="{{ route('student.attendance.show', ['id' => Auth::user()->id]) }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('student.attendance.show') ? 'sidebar-link-active' : 'text-gray-700 hover:bg-gray-50' }}">
            <i data-lucide="calendar-check" class="w-4 h-4 shrink-0 {{ request()->routeIs('student.attendance.show') ? 'text-indigo-600' : 'text-gray-400' }}"></i>
            <span>Attendance</span>
        </a>
        <a href="{{ route('course.student.list.show', ['student_id' => Auth::user()->id]) }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('course.student.list.show') ? 'sidebar-link-active' : 'text-gray-700 hover:bg-gray-50' }}">
            <i data-lucide="book-open" class="w-4 h-4 shrink-0 {{ request()->routeIs('course.student.list.show') ? 'text-indigo-600' : 'text-gray-400' }}"></i>
            <span>Courses</span>
        </a>
        @php
            if (session()->has('browse_session_id')) {
                $class_info = \App\Models\Promotion::where('session_id', session('browse_session_id'))->where('student_id', Auth::user()->id)->first();
            } else {
                $latest_session = \App\Models\SchoolSession::latest()->first();
                $class_info = $latest_session ? \App\Models\Promotion::where('session_id', $latest_session->id)->where('student_id', Auth::user()->id)->first() : null;
            }
        @endphp
        @if($class_info)
        <a href="{{ route('section.routine.show', ['class_id' => $class_info->class_id, 'section_id' => $class_info->section_id]) }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors text-gray-700 hover:bg-gray-50">
            <i data-lucide="clock" class="w-4 h-4 shrink-0 text-gray-400"></i>
            <span>Routine</span>
        </a>
        @endif
        @endif

        {{-- Exams / Grades submenu (non-student) --}}
        @if(Auth::user()->role != "student")
        <div x-data="{ open: {{ request()->is('exams*') ? 'true' : 'false' }} }">
            <button @click="open = !open"
                    class="w-full flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->is('exams*') ? 'sidebar-link-active' : 'text-gray-700 hover:bg-gray-50' }}">
                <i data-lucide="file-text" class="w-4 h-4 shrink-0 {{ request()->is('exams*') ? 'text-indigo-600' : 'text-gray-400' }}"></i>
                <span class="flex-1 text-left">Exams / Grades</span>
                <i data-lucide="chevron-down" class="w-3.5 h-3.5 text-gray-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''"></i>
            </button>
            <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="sidebar-submenu mt-0.5 space-y-0.5">
                <a href="{{ route('exam.list.show') }}"
                   class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('exam.list.show') ? 'sidebar-link-active' : 'text-gray-600 hover:bg-gray-50' }}">
                    <i data-lucide="list" class="w-3.5 h-3.5 shrink-0 text-gray-400"></i>
                    View Exams
                </a>
                @if (Auth::user()->role == "admin" || Auth::user()->role == "teacher")
                <a href="{{ route('exam.create.show') }}"
                   class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('exam.create.show') ? 'sidebar-link-active' : 'text-gray-600 hover:bg-gray-50' }}">
                    <i data-lucide="file-plus" class="w-3.5 h-3.5 shrink-0 text-gray-400"></i>
                    Create Exam
                </a>
                @endif
                @if (Auth::user()->role == "admin")
                <a href="{{ route('exam.grade.system.create') }}"
                   class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('exam.grade.system.create') ? 'sidebar-link-active' : 'text-gray-600 hover:bg-gray-50' }}">
                    <i data-lucide="plus-circle" class="w-3.5 h-3.5 shrink-0 text-gray-400"></i>
                    Add Grade System
                </a>
                @endif
                <a href="{{ route('exam.grade.system.index') }}"
                   class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('exam.grade.system.index') ? 'sidebar-link-active' : 'text-gray-600 hover:bg-gray-50' }}">
                    <i data-lucide="table" class="w-3.5 h-3.5 shrink-0 text-gray-400"></i>
                    Grade Systems
                </a>
            </div>
        </div>
        @endif

        {{-- CONTENT group label (admin only) --}}
        @if (Auth::user()->role == "admin")
        <p class="px-3 pt-4 pb-1 text-xs font-semibold text-gray-400 uppercase tracking-wider">Content</p>

        <a href="{{ route('notice.create') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->is('notice*') ? 'sidebar-link-active' : 'text-gray-700 hover:bg-gray-50' }}">
            <i data-lucide="megaphone" class="w-4 h-4 shrink-0 {{ request()->is('notice*') ? 'text-indigo-600' : 'text-gray-400' }}"></i>
            <span>Notice</span>
        </a>

        <a href="{{ route('events.show') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->is('calendar-event*') ? 'sidebar-link-active' : 'text-gray-700 hover:bg-gray-50' }}">
            <i data-lucide="calendar-days" class="w-4 h-4 shrink-0 {{ request()->is('calendar-event*') ? 'text-indigo-600' : 'text-gray-400' }}"></i>
            <span>Events</span>
        </a>

        <a href="{{ route('class.syllabus.create') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->is('syllabus*') ? 'sidebar-link-active' : 'text-gray-700 hover:bg-gray-50' }}">
            <i data-lucide="book-marked" class="w-4 h-4 shrink-0 {{ request()->is('syllabus*') ? 'text-indigo-600' : 'text-gray-400' }}"></i>
            <span>Syllabus</span>
        </a>

        <a href="{{ route('section.routine.create') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->is('routine*') ? 'sidebar-link-active' : 'text-gray-700 hover:bg-gray-50' }}">
            <i data-lucide="clock-4" class="w-4 h-4 shrink-0 {{ request()->is('routine*') ? 'text-indigo-600' : 'text-gray-400' }}"></i>
            <span>Routine</span>
        </a>

        {{-- SYSTEM group label (admin only) --}}
        <p class="px-3 pt-4 pb-1 text-xs font-semibold text-gray-400 uppercase tracking-wider">System</p>

        <a href="{{ route('academic.settings.show') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->is('academics*') ? 'sidebar-link-active' : 'text-gray-700 hover:bg-gray-50' }}">
            <i data-lucide="settings" class="w-4 h-4 shrink-0 {{ request()->is('academics*') ? 'text-indigo-600' : 'text-gray-400' }}"></i>
            <span>Academic Settings</span>
        </a>

        @if (!session()->has('browse_session_id'))
        <a href="{{ url('promotions/index') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->is('promotions*') ? 'sidebar-link-active' : 'text-gray-700 hover:bg-gray-50' }}">
            <i data-lucide="arrow-up-circle" class="w-4 h-4 shrink-0 {{ request()->is('promotions*') ? 'text-indigo-600' : 'text-gray-400' }}"></i>
            <span>Promotions</span>
        </a>
        @endif

        {{-- FINANCE group label --}}
        <p class="px-3 pt-4 pb-1 text-xs font-semibold text-gray-400 uppercase tracking-wider">Finance</p>

        <span class="flex items-center gap-3 px-3 py-2 rounded-lg text-gray-400 cursor-not-allowed select-none">
            <i data-lucide="credit-card" class="w-4 h-4 shrink-0"></i>
            <span>Payment <span class="text-xs ml-1 bg-gray-100 text-gray-400 rounded px-1">Soon</span></span>
        </span>

        <span class="flex items-center gap-3 px-3 py-2 rounded-lg text-gray-400 cursor-not-allowed select-none">
            <i data-lucide="users-2" class="w-4 h-4 shrink-0"></i>
            <span>Staff <span class="text-xs ml-1 bg-gray-100 text-gray-400 rounded px-1">Soon</span></span>
        </span>

        <span class="flex items-center gap-3 px-3 py-2 rounded-lg text-gray-400 cursor-not-allowed select-none">
            <i data-lucide="library" class="w-4 h-4 shrink-0"></i>
            <span>Library <span class="text-xs ml-1 bg-gray-100 text-gray-400 rounded px-1">Soon</span></span>
        </span>
        @endif

        {{-- Payment (non-admin) --}}
        @if (Auth::user()->role != "admin")
        <span class="flex items-center gap-3 px-3 py-2 rounded-lg text-gray-400 cursor-not-allowed select-none">
            <i data-lucide="credit-card" class="w-4 h-4 shrink-0"></i>
            <span>Payment <span class="text-xs ml-1 bg-gray-100 text-gray-400 rounded px-1">Soon</span></span>
        </span>
        @endif

    </nav>
</aside>
