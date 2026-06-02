<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
       class="fixed left-0 top-0 h-full w-[260px] bg-white border-r border-gray-200 z-50 flex flex-col transition-transform duration-300 overflow-y-auto">

    {{-- Logo --}}
    <div class="h-16 flex items-center px-5 border-b border-gray-200 shrink-0">
        <a href="{{ url('/') }}" class="flex items-center gap-2.5 hover:opacity-80 transition-opacity">
            @if(isset($school_setting) && $school_setting->logo)
                <img src="{{ asset($school_setting->logo) }}"
                     alt="{{ $school_setting->school_name ?? config('app.name', 'Unifiedtransform') }}"
                     class="h-8 w-auto object-contain">
            @else
                <img src="{{ asset('imgs/logo.png') }}" alt="{{ config('app.name', 'Unifiedtransform') }}"
                     class="h-8 w-auto object-contain">
            @endif
            <span
                class="font-heading font-semibold text-base text-indigo-600 truncate">{{ $school_setting->school_name ?? config('app.name', 'Unifiedtransform') }}</span>
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
        <a href="{{ url('home') }}" id="tour-dashboard"
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
                $academic_setting = \App\Models\AcademicSetting::first();
                $active_session_id = $academic_setting?->active_session_id;
                if ($active_session_id) {
                    $classCount = \App\Models\SchoolClass::where('session_id', $active_session_id)->count();
                } else {
                    $latest_session = \App\Models\SchoolSession::latest()->first();
                    $classCount = $latest_session ? \App\Models\SchoolClass::where('session_id', $latest_session->id)->count() : 0;
                }
            }
        @endphp
            <a href="{{ url('classes') }}" id="tour-classes"
           class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->is('classes') ? 'sidebar-link-active' : 'text-gray-700 hover:bg-gray-50' }}">
            <i data-lucide="git-branch" class="w-4 h-4 shrink-0 {{ request()->is('classes') ? 'text-indigo-600' : 'text-gray-400' }}"></i>
            <span class="flex-1">Classes</span>
            <span class="text-xs font-medium text-gray-400 bg-gray-100 rounded-full px-1.5 py-0.5">{{ $classCount }}</span>
        </a>
        @endcan

        {{-- Students submenu (admin/teacher only) --}}
        @if(Auth::user()->role != "student")
            <div x-data="{ open: {{ request()->is('students*') ? 'true' : 'false' }} }" id="tour-students">
            <button @click="open = !open"
                    class="w-full flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->is('students*') ? 'sidebar-link-active' : 'text-gray-700 hover:bg-gray-50' }}">
                <i data-lucide="users" class="w-4 h-4 shrink-0 {{ request()->is('students*') ? 'text-indigo-600' : 'text-gray-400' }}"></i>
                <span class="flex-1 text-left">Students</span>
                <i data-lucide="chevron-down" class="w-3.5 h-3.5 text-gray-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''"></i>
            </button>
            <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="sidebar-submenu mt-0.5 space-y-0.5">
                <a href="{{ route('student.list.show') }}" id="tour-student-list"
                   class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('student.list.show') ? 'sidebar-link-active' : 'text-gray-600 hover:bg-gray-50' }}">
                    <i data-lucide="user-search" class="w-3.5 h-3.5 shrink-0 text-gray-400"></i>
                    View Students
                </a>
                @if (!session()->has('browse_session_id') && Auth::user()->role == "admin")
                    <a href="{{ route('student.create.show') }}" id="tour-student-create"
                   class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('student.create.show') ? 'sidebar-link-active' : 'text-gray-600 hover:bg-gray-50' }}">
                    <i data-lucide="user-plus" class="w-3.5 h-3.5 shrink-0 text-gray-400"></i>
                    Add Student
                </a>
                @endif
            </div>
        </div>

        {{-- Teachers submenu --}}
            <div x-data="{ open: {{ request()->is('teachers*') ? 'true' : 'false' }} }" id="tour-teachers">
            <button @click="open = !open"
                    class="w-full flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->is('teachers*') ? 'sidebar-link-active' : 'text-gray-700 hover:bg-gray-50' }}">
                <i data-lucide="user-check" class="w-4 h-4 shrink-0 {{ request()->is('teachers*') ? 'text-indigo-600' : 'text-gray-400' }}"></i>
                <span class="flex-1 text-left">Teachers</span>
                <i data-lucide="chevron-down" class="w-3.5 h-3.5 text-gray-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''"></i>
            </button>
            <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="sidebar-submenu mt-0.5 space-y-0.5">
                <a href="{{ route('teacher.list.show') }}" id="tour-teacher-list"
                   class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('teacher.list.show') ? 'sidebar-link-active' : 'text-gray-600 hover:bg-gray-50' }}">
                    <i data-lucide="user-search" class="w-3.5 h-3.5 shrink-0 text-gray-400"></i>
                    View Teachers
                </a>
                @if (!session()->has('browse_session_id') && Auth::user()->role == "admin")
                    <a href="{{ route('teacher.create.show') }}" id="tour-teacher-create"
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
            <a href="{{ route('course.teacher.list.show', ['teacher_id' => Auth::user()->id]) }}" id="tour-my-courses"
           class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ (request()->is('courses/teacher*') || request()->is('courses/assignments*')) ? 'sidebar-link-active' : 'text-gray-700 hover:bg-gray-50' }}">
            <i data-lucide="book-open" class="w-4 h-4 shrink-0{{ (request()->is('courses/teacher*') || request()->is('courses/assignments*')) ? ' text-indigo-600' : ' text-gray-400' }}"></i>
            <span>My Courses</span>
        </a>
        @endif

        {{-- Student-only nav items --}}
        @if(Auth::user()->role == "student")
            <a href="{{ route('student.attendance.show', ['id' => Auth::user()->id]) }}" id="tour-student-attendance"
           class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('student.attendance.show') ? 'sidebar-link-active' : 'text-gray-700 hover:bg-gray-50' }}">
            <i data-lucide="calendar-check" class="w-4 h-4 shrink-0 {{ request()->routeIs('student.attendance.show') ? 'text-indigo-600' : 'text-gray-400' }}"></i>
            <span>Attendance</span>
        </a>
            <a href="{{ route('course.student.list.show', ['student_id' => Auth::user()->id]) }}"
               id="tour-student-courses"
           class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('course.student.list.show') ? 'sidebar-link-active' : 'text-gray-700 hover:bg-gray-50' }}">
            <i data-lucide="book-open" class="w-4 h-4 shrink-0 {{ request()->routeIs('course.student.list.show') ? 'text-indigo-600' : 'text-gray-400' }}"></i>
            <span>Courses</span>
        </a>
        @php
            if (session()->has('browse_session_id')) {
                $class_info = \App\Models\Promotion::where('session_id', session('browse_session_id'))->where('student_id', Auth::user()->id)->first();
            } else {
                $academic_setting = \App\Models\AcademicSetting::first();
                $active_session_id = $academic_setting?->active_session_id;
                if ($active_session_id) {
                    $class_info = \App\Models\Promotion::where('session_id', $active_session_id)->where('student_id', Auth::user()->id)->first();
                } else {
                    $latest_session = \App\Models\SchoolSession::latest()->first();
                    $class_info = $latest_session ? \App\Models\Promotion::where('session_id', $latest_session->id)->where('student_id', Auth::user()->id)->first() : null;
                }
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
            <div x-data="{ open: {{ request()->is('exams*') ? 'true' : 'false' }} }" id="tour-exams">
            <button @click="open = !open"
                    class="w-full flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->is('exams*') ? 'sidebar-link-active' : 'text-gray-700 hover:bg-gray-50' }}">
                <i data-lucide="file-text" class="w-4 h-4 shrink-0 {{ request()->is('exams*') ? 'text-indigo-600' : 'text-gray-400' }}"></i>
                <span class="flex-1 text-left">Exams / Grades</span>
                <i data-lucide="chevron-down" class="w-3.5 h-3.5 text-gray-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''"></i>
            </button>
            <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="sidebar-submenu mt-0.5 space-y-0.5">
                <a href="{{ route('exam.list.show') }}" id="tour-exam-list"
                   class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('exam.list.show') ? 'sidebar-link-active' : 'text-gray-600 hover:bg-gray-50' }}">
                    <i data-lucide="list" class="w-3.5 h-3.5 shrink-0 text-gray-400"></i>
                    View Exams
                </a>
                @if (Auth::user()->role == "admin" || Auth::user()->role == "teacher")
                    <a href="{{ route('exam.create.show') }}" id="tour-exam-create"
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
                <a href="{{ route('course.mark.create') }}" id="tour-exam-marks"
                   class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('course.mark.create') ? 'sidebar-link-active' : 'text-gray-600 hover:bg-gray-50' }}">
                    <i data-lucide="edit-3" class="w-3.5 h-3.5 shrink-0 text-gray-400"></i>
                    Give Marks
                </a>
            </div>
        </div>
        @endif

        {{-- CONTENT group label (admin only) --}}
        @if (Auth::user()->role == "admin")
        <p class="px-3 pt-4 pb-1 text-xs font-semibold text-gray-400 uppercase tracking-wider">Content</p>

            <a href="{{ route('notice.create') }}" id="tour-notice"
           class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->is('notice*') ? 'sidebar-link-active' : 'text-gray-700 hover:bg-gray-50' }}">
            <i data-lucide="megaphone" class="w-4 h-4 shrink-0 {{ request()->is('notice*') ? 'text-indigo-600' : 'text-gray-400' }}"></i>
            <span>Notice</span>
        </a>

            <a href="{{ route('events.show') }}" id="tour-events"
           class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->is('calendar-event*') ? 'sidebar-link-active' : 'text-gray-700 hover:bg-gray-50' }}">
            <i data-lucide="calendar-days" class="w-4 h-4 shrink-0 {{ request()->is('calendar-event*') ? 'text-indigo-600' : 'text-gray-400' }}"></i>
            <span>Events</span>
        </a>

            <a href="{{ route('class.syllabus.create') }}" id="tour-syllabus"
           class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->is('syllabus*') ? 'sidebar-link-active' : 'text-gray-700 hover:bg-gray-50' }}">
            <i data-lucide="book-marked" class="w-4 h-4 shrink-0 {{ request()->is('syllabus*') ? 'text-indigo-600' : 'text-gray-400' }}"></i>
            <span>Syllabus</span>
        </a>

            <a href="{{ route('section.routine.create') }}" id="tour-routine"
           class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->is('routine*') ? 'sidebar-link-active' : 'text-gray-700 hover:bg-gray-50' }}">
            <i data-lucide="clock-4" class="w-4 h-4 shrink-0 {{ request()->is('routine*') ? 'text-indigo-600' : 'text-gray-400' }}"></i>
            <span>Routine</span>
        </a>

        {{-- SYSTEM group label (admin only) --}}
        <p class="px-3 pt-4 pb-1 text-xs font-semibold text-gray-400 uppercase tracking-wider">System</p>

            <a href="{{ route('academic.settings.show') }}" id="tour-settings"
           class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->is('academics*') ? 'sidebar-link-active' : 'text-gray-700 hover:bg-gray-50' }}">
            <i data-lucide="settings" class="w-4 h-4 shrink-0 {{ request()->is('academics*') ? 'text-indigo-600' : 'text-gray-400' }}"></i>
            <span>System Settings</span>
        </a>

            @can('manage roles')
                <a href="{{ route('roles.index') }}" id="tour-roles"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->is('roles*') ? 'sidebar-link-active' : 'text-gray-700 hover:bg-gray-50' }}">
                    <i data-lucide="shield-check"
                       class="w-4 h-4 shrink-0 {{ request()->is('roles*') ? 'text-indigo-600' : 'text-gray-400' }}"></i>
                    <span>Roles & Permissions</span>
                </a>
            @endcan

        @if (!session()->has('browse_session_id'))
                <a href="{{ url('promotions/index') }}" id="tour-promotions"
           class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->is('promotions*') ? 'sidebar-link-active' : 'text-gray-700 hover:bg-gray-50' }}">
            <i data-lucide="arrow-up-circle" class="w-4 h-4 shrink-0 {{ request()->is('promotions*') ? 'text-indigo-600' : 'text-gray-400' }}"></i>
            <span>Promotions</span>
        </a>
        @endif
        @endif

        {{-- Finance group label --}}
        @can('finance.view')
            <p class="px-3 pt-4 pb-1 text-xs font-semibold text-gray-400 uppercase tracking-wider">Finance</p>

            {{-- Finance Dashboard --}}
            <a href="{{ route('finance.dashboard') }}" id="tour-finance"
               class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('finance.dashboard') ? 'sidebar-link-active' : 'text-gray-700 hover:bg-gray-50' }}">
                <i data-lucide="line-chart"
                   class="w-4 h-4 shrink-0 {{ request()->routeIs('finance.dashboard') ? 'text-indigo-600' : 'text-gray-400' }}"></i>
                <span>Dashboard</span>
            </a>

            {{-- Fees submenu --}}
            <div x-data="{ open: {{ request()->is('finance/fee-*') ? 'true' : 'false' }} }" id="tour-finance-fees">
                <button @click="open = !open"
                        class="w-full flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->is('finance/fee-*') ? 'sidebar-link-active' : 'text-gray-700 hover:bg-gray-50' }}">
                    <i data-lucide="banknote"
                       class="w-4 h-4 shrink-0 {{ request()->is('finance/fee-*') ? 'text-indigo-600' : 'text-gray-400' }}"></i>
                    <span class="flex-1 text-left">Fees</span>
                    <i data-lucide="chevron-down" class="w-3.5 h-3.5 text-gray-400 transition-transform duration-200"
                       :class="open ? 'rotate-180' : ''"></i>
                </button>
                <div x-show="open" x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="opacity-0 -translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0" class="sidebar-submenu mt-0.5 space-y-0.5">
                    <a href="{{ route('finance.fee-types.index') }}"
                       class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('finance.fee-types.*') ? 'sidebar-link-active' : 'text-gray-600 hover:bg-gray-50' }}">
                        <i data-lucide="list" class="w-3.5 h-3.5 shrink-0 text-gray-400"></i>
                        Fee Types
                    </a>
                    <a href="{{ route('finance.fee-structures.index') }}"
                       class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('finance.fee-structures.*') ? 'sidebar-link-active' : 'text-gray-600 hover:bg-gray-50' }}">
                        <i data-lucide="layers" class="w-3.5 h-3.5 shrink-0 text-gray-400"></i>
                        Fee Structures
                    </a>
                </div>
            </div>

            {{-- Billing & Payments submenu --}}
            <div id="tour-finance-billing"
                x-data="{ open: {{ (request()->is('finance/invoices*') || request()->is('finance/payments*')) ? 'true' : 'false' }} }">
                <button @click="open = !open"
                        class="w-full flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ (request()->is('finance/invoices*') || request()->is('finance/payments*')) ? 'sidebar-link-active' : 'text-gray-700 hover:bg-gray-50' }}">
                    <i data-lucide="credit-card"
                       class="w-4 h-4 shrink-0 {{ (request()->is('finance/invoices*') || request()->is('finance/payments*')) ? 'text-indigo-600' : 'text-gray-400' }}"></i>
                    <span class="flex-1 text-left">Billing & Payments</span>
                    <i data-lucide="chevron-down" class="w-3.5 h-3.5 text-gray-400 transition-transform duration-200"
                       :class="open ? 'rotate-180' : ''"></i>
                </button>
                <div x-show="open" x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="opacity-0 -translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0" class="sidebar-submenu mt-0.5 space-y-0.5">
                    <a href="{{ route('finance.invoices.index') }}"
                       class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('finance.invoices.*') ? 'sidebar-link-active' : 'text-gray-600 hover:bg-gray-50' }}">
                        <i data-lucide="file-text" class="w-3.5 h-3.5 shrink-0 text-gray-400"></i>
                        Invoices
                    </a>
                    <a href="{{ route('finance.payments.index') }}"
                       class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('finance.payments.*') ? 'sidebar-link-active' : 'text-gray-600 hover:bg-gray-50' }}">
                        <i data-lucide="receipt" class="w-3.5 h-3.5 shrink-0 text-gray-400"></i>
                        Payments
                    </a>
                </div>
            </div>

            {{-- Discounts & Scholarships submenu --}}
            <div
                x-data="{ open: {{ (request()->is('finance/discounts*') || request()->is('finance/scholarships*')) ? 'true' : 'false' }} }">
                <button @click="open = !open"
                        class="w-full flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ (request()->is('finance/discounts*') || request()->is('finance/scholarships*')) ? 'sidebar-link-active' : 'text-gray-700 hover:bg-gray-50' }}">
                    <i data-lucide="tags"
                       class="w-4 h-4 shrink-0 {{ (request()->is('finance/discounts*') || request()->is('finance/scholarships*')) ? 'text-indigo-600' : 'text-gray-400' }}"></i>
                    <span class="flex-1 text-left">Discounts</span>
                    <i data-lucide="chevron-down" class="w-3.5 h-3.5 text-gray-400 transition-transform duration-200"
                       :class="open ? 'rotate-180' : ''"></i>
                </button>
                <div x-show="open" x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="opacity-0 -translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0" class="sidebar-submenu mt-0.5 space-y-0.5">
                    <a href="{{ route('finance.discounts.index') }}"
                       class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('finance.discounts.*') ? 'sidebar-link-active' : 'text-gray-600 hover:bg-gray-50' }}">
                        <i data-lucide="percent" class="w-3.5 h-3.5 shrink-0 text-gray-400"></i>
                        General Discounts
                    </a>
                    <a href="{{ route('finance.scholarships.index') }}"
                       class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('finance.scholarships.*') ? 'sidebar-link-active' : 'text-gray-600 hover:bg-gray-50' }}">
                        <i data-lucide="graduation-cap" class="w-3.5 h-3.5 shrink-0 text-gray-400"></i>
                        Scholarships
                    </a>
                </div>
            </div>

            {{-- Expenses & Vendors submenu --}}
            <div id="tour-finance-expenses"
                x-data="{ open: {{ (request()->is('finance/expenses*') || request()->is('finance/vendors*') || request()->is('finance/budgets*')) ? 'true' : 'false' }} }">
                <button @click="open = !open"
                        class="w-full flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ (request()->is('finance/expenses*') || request()->is('finance/vendors*') || request()->is('finance/budgets*')) ? 'sidebar-link-active' : 'text-gray-700 hover:bg-gray-50' }}">
                    <i data-lucide="wallet"
                       class="w-4 h-4 shrink-0 {{ (request()->is('finance/expenses*') || request()->is('finance/vendors*') || request()->is('finance/budgets*')) ? 'text-indigo-600' : 'text-gray-400' }}"></i>
                    <span class="flex-1 text-left">Expenses</span>
                    <i data-lucide="chevron-down" class="w-3.5 h-3.5 text-gray-400 transition-transform duration-200"
                       :class="open ? 'rotate-180' : ''"></i>
                </button>
                <div x-show="open" x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="opacity-0 -translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0" class="sidebar-submenu mt-0.5 space-y-0.5">
                    <a href="{{ route('finance.expenses.index') }}"
                       class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('finance.expenses.*') ? 'sidebar-link-active' : 'text-gray-600 hover:bg-gray-50' }}">
                        <i data-lucide="minus-circle" class="w-3.5 h-3.5 shrink-0 text-gray-400"></i>
                        Manage Expenses
                    </a>
                    <a href="{{ route('finance.vendors.index') }}"
                       class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('finance.vendors.*') ? 'sidebar-link-active' : 'text-gray-600 hover:bg-gray-50' }}">
                        <i data-lucide="truck" class="w-3.5 h-3.5 shrink-0 text-gray-400"></i>
                        Vendors
                    </a>
                    <a href="{{ route('finance.budgets.index') }}"
                       class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('finance.budgets.*') ? 'sidebar-link-active' : 'text-gray-600 hover:bg-gray-50' }}">
                        <i data-lucide="pie-chart" class="w-3.5 h-3.5 shrink-0 text-gray-400"></i>
                        Budgets
                    </a>
                </div>
            </div>

            {{-- Reports --}}
            <a href="{{ route('finance.reports.index') }}" id="tour-finance-reports"
               class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->is('finance/reports*') ? 'sidebar-link-active' : 'text-gray-700 hover:bg-gray-50' }}">
                <i data-lucide="bar-chart-3"
                   class="w-4 h-4 shrink-0 {{ request()->is('finance/reports*') ? 'text-indigo-600' : 'text-gray-400' }}"></i>
                <span>Reports</span>
            </a>
        @endcan

    </nav>
</aside>
