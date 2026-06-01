<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('page-title', config('app.name', 'Unifiedtransform')) — {{ config('app.name', 'Unifiedtransform') }}</title>

    <!-- Favicons -->
    <link rel="shortcut icon" href="{{ asset('favicon_io/favicon.ico') }}">
    <link rel="shortcut icon" sizes="16x16" href="{{ asset('favicon_io/favicon-16x16.png') }}">
    <link rel="shortcut icon" sizes="32x32" href="{{ asset('favicon_io/favicon-32x32.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon_io/apple-touch-icon.png') }}">
    <link rel="icon" href="{{ asset('favicon_io/android-chrome-192x192.png') }}" sizes="192x192">
    <link rel="icon" href="{{ asset('favicon_io/android-chrome-512x512.png') }}" sizes="512x512">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Inter:wght@400;500;600&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    <!-- jQuery must load before everything else (FullCalendar, DataTables inline scripts) -->
    <script src="{{ asset('js/jquery.min.js') }}"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Custom component overrides (FullCalendar, CKEditor, sidebar) -->
    <link rel="stylesheet" href="{{ asset('css/app-custom.css') }}">

    <!-- Page-specific head injections (FullCalendar CDN links etc.) -->
    @stack('head-scripts')
</head>
<body class="bg-gray-50 text-gray-900 font-sans antialiased">

<div x-data="{ sidebarOpen: window.innerWidth >= 1024 }" @resize.window="sidebarOpen = window.innerWidth >= 1024">

    @auth
        {{-- Sidebar --}}
        @include('layouts.left-menu')

        {{-- Overlay for mobile --}}
        <div x-show="sidebarOpen && window.innerWidth < 1024"
             @click="sidebarOpen = false"
             class="fixed inset-0 z-40 bg-black/30 lg:hidden"
             x-transition:enter="transition-opacity ease-linear duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"></div>

        {{-- Main column --}}
        <div :class="sidebarOpen ? 'lg:ml-[260px]' : ''"
             class="transition-all duration-300 min-h-screen flex flex-col">

            {{-- Top bar --}}
            @php
                $latest_school_session = \App\Models\SchoolSession::latest()->first();
                $current_school_session_name = $latest_school_session?->session_name;

                $academic_setting = \App\Models\AcademicSetting::first();
                $active_semester = $academic_setting?->active_semester_id ? \App\Models\Semester::find($academic_setting->active_semester_id) : null;
            @endphp
            <header class="h-16 sticky top-0 z-30 bg-white border-b border-gray-200 flex items-center px-4 lg:px-6 gap-3 shrink-0">

                {{-- Hamburger --}}
                <button @click="sidebarOpen = !sidebarOpen"
                        class="p-1.5 rounded-lg text-gray-500 hover:bg-gray-100 hover:text-gray-900 transition-colors"
                        aria-label="Toggle sidebar">
                    <i data-lucide="menu" class="w-5 h-5"></i>
                </button>

                {{-- Breadcrumb / page title --}}
                <div class="flex-1 min-w-0">
                    <span class="text-sm font-medium text-gray-900 truncate">
                        @yield('page-title', 'Dashboard')
                    </span>
                </div>

                {{-- Academic session banner --}}
                <div class="hidden md:flex items-center gap-2">
                    @if (session()->has('browse_session_name') && session('browse_session_name') !== $current_school_session_name)
                        <span class="inline-flex items-center gap-1.5 text-xs font-medium text-amber-700 bg-amber-50 border border-amber-200 rounded-full px-3 py-1">
                            <i data-lucide="alert-triangle" class="w-3.5 h-3.5"></i>
                            Browsing: {{ session('browse_session_name') }}
                        </span>
                    @elseif($current_school_session_name)
                        <span class="inline-flex items-center gap-1.5 text-xs font-medium text-gray-500 bg-gray-100 rounded-full px-3 py-1">
                            <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                            {{ $current_school_session_name }}
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 text-xs font-medium text-red-700 bg-red-50 border border-red-200 rounded-full px-3 py-1">
                            <i data-lucide="alert-circle" class="w-3.5 h-3.5"></i>
                            No Academic Session
                        </span>
                    @endif

                    @if($active_semester)
                        <span
                            class="inline-flex items-center gap-1.5 text-xs font-medium text-indigo-700 bg-indigo-50 border border-indigo-100 rounded-full px-3 py-1">
                            <i data-lucide="layers" class="w-3.5 h-3.5"></i>
                            {{ $active_semester->semester_name }}
                        </span>
                    @endif
                </div>

                {{-- User dropdown --}}
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open"
                            class="flex items-center gap-2 pl-2 pr-3 py-1.5 rounded-lg hover:bg-gray-100 transition-colors text-sm"
                            aria-haspopup="true" :aria-expanded="open">
                        <div class="w-7 h-7 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-semibold text-xs shrink-0">
                            {{ strtoupper(substr(Auth::user()->first_name, 0, 1)) }}
                        </div>
                        <span class="hidden sm:block font-medium text-gray-800 max-w-[120px] truncate">
                            {{ Auth::user()->first_name }}
                        </span>
                        <i data-lucide="chevron-down" class="w-4 h-4 text-gray-400 hidden sm:block"></i>
                    </button>

                    <div x-show="open"
                         @click.outside="open = false"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-52 bg-white rounded-xl shadow-card border border-gray-200 py-1 z-50 origin-top-right">

                        <div class="px-3 py-2 border-b border-gray-100">
                            <p class="text-sm font-semibold text-gray-900">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</p>
                            <p class="text-xs text-gray-500 capitalize mt-0.5">{{ Auth::user()->role }}</p>
                        </div>

                        <a href="{{ route('password.edit') }}"
                           class="flex items-center gap-2.5 px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                            <i data-lucide="key" class="w-4 h-4 text-gray-400"></i>
                            Change Password
                        </a>

                        <a href="{{ route('logout') }}"
                           class="flex items-center gap-2.5 px-3 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i data-lucide="log-out" class="w-4 h-4"></i>
                            {{ __('Logout') }}
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                            @csrf
                        </form>
                    </div>
                </div>
            </header>

            {{-- Content area --}}
            <main class="flex-1 p-4 lg:p-6">
                <div class="max-w-screen-xl mx-auto">
                    @yield('content')
                </div>
            </main>

            @include('layouts.footer')
        </div>

    @else
        {{-- Unauthenticated: centered layout (login, password reset, etc.) --}}
        <div class="min-h-screen flex">
            {{-- Left branding panel --}}
            <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-indigo-600 to-indigo-800 flex-col items-center justify-center p-12 text-white">
                <div class="mb-6 bg-white/15 rounded-2xl p-5 backdrop-blur-sm">
                    <img src="{{ asset('imgs/logo.png') }}" alt="{{ config('app.name', 'Unifiedtransform') }}" class="h-20 w-auto object-contain">
                </div>
                <h1 class="font-heading text-3xl font-bold mb-3 text-center">{{ config('app.name', 'Unifiedtransform') }}</h1>
                <p class="text-indigo-200 text-center text-base leading-relaxed max-w-xs">
                    Modern school management — academic sessions, attendance, grading, and more.
                </p>
            </div>
            {{-- Right form panel --}}
            <div class="flex-1 flex items-center justify-center p-6 bg-white">
                <div class="w-full max-w-md">
                    <div class="mb-8 lg:hidden text-center">
                        <img src="{{ asset('imgs/logo.png') }}" alt="{{ config('app.name', 'Unifiedtransform') }}" class="h-12 w-auto object-contain mx-auto mb-2">
                        <h2 class="font-heading text-xl font-bold text-gray-900">{{ config('app.name', 'Unifiedtransform') }}</h2>
                    </div>
                    @yield('content')
                </div>
            </div>
        </div>
    @endauth

</div>


@stack('scripts')
</body>
</html>
