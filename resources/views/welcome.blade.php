<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Therco — School Management System</title>

    <link rel="shortcut icon" href="{{asset('favicon_io/favicon.ico')}}">
    <link rel="shortcut icon" sizes="16x16" href="{{asset('favicon_io/favicon-16x16.png')}}">
    <link rel="shortcut icon" sizes="32x32" href="{{asset('favicon_io/favicon-32x32.png')}}">
    <link rel="apple-touch-icon" href="{{asset('favicon_io/apple-touch-icon.png')}}">
    <link rel="icon" href="{{asset('favicon_io/android-chrome-192x192.png')}}" sizes="192x192">
    <link rel="icon" href="{{asset('favicon_io/android-chrome-512x512.png')}}" sizes="512x512">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CDN (replace with compiled asset in production) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="antialiased bg-white text-gray-900">

    <!-- Navigation -->
    <header class="fixed top-0 inset-x-0 z-50 bg-white/80 backdrop-blur border-b border-gray-100">
        <div class="max-w-6xl mx-auto px-6 h-16 flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <img src="{{asset('favicon_io/android-chrome-192x192.png')}}" class="w-8 h-8 rounded-lg" alt="Logo">
                <span class="font-bold text-gray-900 text-lg tracking-tight">Therco</span>
            </div>
            @if (Route::has('login'))
            <nav class="flex items-center gap-3">
                @auth
                    <a href="{{ url('/home') }}"
                       class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm px-4 py-2 rounded-lg transition-colors">
                        Go to Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}"
                       class="text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors">
                        Sign in
                    </a>
                @endauth
            </nav>
            @endif
        </div>
    </header>

    <!-- Hero Section -->
    <section class="relative pt-16 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-indigo-600 via-indigo-700 to-violet-800"></div>
        <!-- Decorative blobs -->
        <div class="absolute top-0 right-0 w-96 h-96 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/3 blur-3xl"></div>
        <div class="absolute bottom-0 left-0 w-72 h-72 bg-violet-400/10 rounded-full translate-y-1/2 -translate-x-1/4 blur-3xl"></div>

        <div class="relative max-w-6xl mx-auto px-6 py-28 text-center">
            <span class="inline-flex items-center gap-2 bg-white/10 text-white/90 text-xs font-medium px-3 py-1.5 rounded-full mb-6 border border-white/20">
                School Management &amp; Accounting
            </span>
            <h1 class="text-4xl sm:text-5xl md:text-6xl font-extrabold text-white leading-tight tracking-tight mb-6">
                Everything your school<br class="hidden sm:block"> needs, in one place
            </h1>
            <p class="text-lg text-indigo-100 max-w-2xl mx-auto mb-10">
                Manage students, teachers, attendance, grades, and accounting — all from a single, beautifully simple platform.
            </p>
            <div class="flex flex-wrap items-center justify-center gap-4">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/home') }}"
                           class="inline-flex items-center gap-2 bg-white text-indigo-700 hover:bg-indigo-50 font-semibold text-sm px-6 py-3 rounded-xl shadow-lg transition-colors">
                            Go to Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                           class="inline-flex items-center gap-2 bg-white text-indigo-700 hover:bg-indigo-50 font-semibold text-sm px-6 py-3 rounded-xl shadow-lg transition-colors">
                            Sign In
                        </a>
                    @endauth
                @endif
            </div>
        </div>

        <!-- Wave divider -->
        <div class="relative">
            <svg viewBox="0 0 1440 60" fill="none" xmlns="http://www.w3.org/2000/svg" class="block w-full text-white">
                <path d="M0 60L60 50C120 40 240 20 360 15C480 10 600 20 720 27.5C840 35 960 40 1080 37.5C1200 35 1320 25 1380 20L1440 15V60H1380C1320 60 1200 60 1080 60C960 60 840 60 720 60C600 60 480 60 360 60C240 60 120 60 60 60H0Z" fill="currentColor"/>
            </svg>
        </div>
    </section>

    <!-- Features Grid -->
    <section class="bg-white py-20">
        <div class="max-w-6xl mx-auto px-6">
            <div class="text-center mb-14">
                <h2 class="text-3xl font-bold text-gray-900 mb-3">Built for modern schools</h2>
                <p class="text-gray-500 max-w-xl mx-auto">Everything you need to run a school efficiently, from academic management to financial tracking.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

                <!-- Feature 1 -->
                <div class="group p-6 rounded-2xl border border-gray-100 hover:border-indigo-200 hover:shadow-md transition-all">
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 14l6.16-3.422A12.083 12.083 0 0112 21.5a12.083 12.083 0 01-6.16-10.922L12 14z"/></svg>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-1.5">Academic Management</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">Manage sessions, semesters, classes, sections, and courses with ease. Stay organised across every academic year.</p>
                </div>

                <!-- Feature 2 -->
                <div class="group p-6 rounded-2xl border border-gray-100 hover:border-indigo-200 hover:shadow-md transition-all">
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-1.5">Student &amp; Teacher Profiles</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">Maintain detailed records for every student and teacher. Role-based access ensures the right people see the right data.</p>
                </div>

                <!-- Feature 3 -->
                <div class="group p-6 rounded-2xl border border-gray-100 hover:border-indigo-200 hover:shadow-md transition-all">
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-1.5">Attendance Tracking</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">Track attendance by section or by course. Configurable attendance types let you adapt to how your school operates.</p>
                </div>

                <!-- Feature 4 -->
                <div class="group p-6 rounded-2xl border border-gray-100 hover:border-indigo-200 hover:shadow-md transition-all">
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-1.5">Grades &amp; Marks</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">Teachers submit marks and final grades through a controlled workflow. Admins manage submission windows per semester.</p>
                </div>

                <!-- Feature 5 -->
                <div class="group p-6 rounded-2xl border border-gray-100 hover:border-indigo-200 hover:shadow-md transition-all">
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-1.5">Accounting &amp; Finance</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">Built-in accounting tools to track school fees, expenses, and financial summaries — no external spreadsheets needed.</p>
                </div>

                <!-- Feature 6 -->
                <div class="group p-6 rounded-2xl border border-gray-100 hover:border-indigo-200 hover:shadow-md transition-all">
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-1.5">Role-Based Access</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">Separate portals for Admins, Teachers, and Students. Each role sees exactly what they need — nothing more, nothing less.</p>
                </div>

            </div>
        </div>
    </section>

    <!-- CTA Banner -->
    <section class="bg-gradient-to-r from-indigo-600 to-violet-700 py-16">
        <div class="max-w-3xl mx-auto px-6 text-center">
            <h2 class="text-2xl sm:text-3xl font-bold text-white mb-4">Ready to get started?</h2>
            <p class="text-indigo-100 mb-8 text-sm">Sign in to access your dashboard and start managing your school.</p>
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/home') }}"
                       class="inline-flex items-center gap-2 bg-white text-indigo-700 hover:bg-indigo-50 font-semibold text-sm px-6 py-3 rounded-xl shadow-lg transition-colors">
                        Go to Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}"
                       class="inline-flex items-center gap-2 bg-white text-indigo-700 hover:bg-indigo-50 font-semibold text-sm px-6 py-3 rounded-xl shadow-lg transition-colors">
                        Sign In to Your Account
                    </a>
                @endauth
            @endif
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-100 py-8">
        <div class="max-w-6xl mx-auto px-6 flex flex-col sm:flex-row items-center justify-between gap-3">
            <div class="flex items-center gap-2">
                <img src="{{asset('favicon_io/android-chrome-192x192.png')}}" class="w-6 h-6 rounded" alt="Logo">
                <span class="text-sm font-medium text-gray-700">Therco</span>
            </div>
            <p class="text-xs text-gray-400">
                Laravel v{{ Illuminate\Foundation\Application::VERSION }} &middot; PHP v{{ PHP_VERSION }}
            </p>
        </div>
    </footer>

</body>
</html>
