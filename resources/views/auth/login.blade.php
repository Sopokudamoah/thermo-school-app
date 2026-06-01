@extends('layouts.app')

@section('page-title', 'Login')

@section('content')

<h2 class="font-heading text-2xl font-bold text-gray-900 mb-1">Welcome back</h2>
<p class="text-sm text-gray-500 mb-7">Sign in to your account to continue.</p>

<form method="POST" action="{{ route('login') }}" class="space-y-5">
    @csrf

    <div>
        <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('E-Mail Address') }}</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}"
               required autocomplete="email" autofocus
               placeholder="you@school.edu"
               class="block w-full rounded-lg border px-3.5 py-2.5 text-sm text-gray-900 placeholder-gray-400 transition-colors
                      @error('email') border-red-400 ring-2 ring-red-100 @else border-gray-300 @enderror
                      focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
        @error('email')
        <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
            <i data-lucide="alert-circle" class="w-3.5 h-3.5"></i> {{ $message }}
        </p>
        @enderror
    </div>

    <div>
        <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('Password') }}</label>
        <input id="password" type="password" name="password"
               required autocomplete="current-password"
               placeholder="••••••••"
               class="block w-full rounded-lg border px-3.5 py-2.5 text-sm text-gray-900 placeholder-gray-400 transition-colors
                      @error('password') border-red-400 ring-2 ring-red-100 @else border-gray-300 @enderror
                      focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
        @error('password')
        <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
            <i data-lucide="alert-circle" class="w-3.5 h-3.5"></i> {{ $message }}
        </p>
        @enderror
    </div>

    <div class="flex items-center justify-between">
        <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}
                   class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
            <span class="text-sm text-gray-600">{{ __('Remember Me') }}</span>
        </label>
        @if (Route::has('password.request'))
        <a href="{{ route('password.request') }}" class="text-sm text-indigo-600 hover:text-indigo-700 hover:underline">
            Forgot password?
        </a>
        @endif
    </div>

    <button type="submit"
            class="w-full bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white font-medium text-sm py-2.5 rounded-lg transition-colors">
        {{ __('Sign In') }}
    </button>
</form>

@if(config('app.demo_mode'))
    <div class="mt-8 pt-6 border-t border-gray-100" x-data="{
        fillLogin(email, password) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = password;
        }
    }">
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-4 text-center">Demo Accounts</p>
        <div class="grid grid-cols-1 gap-2.5">
            <button @click="fillLogin('admin@ut.com', 'password')"
                    class="flex items-center justify-between px-4 py-2.5 rounded-xl border border-gray-200 bg-white hover:bg-indigo-50 hover:border-indigo-200 transition-all group">
                <div class="flex items-center gap-3">
                    <div
                        class="w-8 h-8 rounded-lg bg-indigo-100 flex items-center justify-center text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                        <i data-lucide="shield-check" class="w-4 h-4"></i>
                    </div>
                    <div class="text-left">
                        <p class="text-sm font-semibold text-gray-700">Super Admin</p>
                        <p class="text-[10px] text-gray-400">admin@ut.com</p>
                    </div>
                </div>
                <i data-lucide="arrow-right"
                   class="w-4 h-4 text-gray-300 group-hover:text-indigo-500 transition-colors"></i>
            </button>

            <button @click="fillLogin('teacher@ut.com', 'password')"
                    class="flex items-center justify-between px-4 py-2.5 rounded-xl border border-gray-200 bg-white hover:bg-indigo-50 hover:border-indigo-200 transition-all group">
                <div class="flex items-center gap-3">
                    <div
                        class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                        <i data-lucide="graduation-cap" class="w-4 h-4"></i>
                    </div>
                    <div class="text-left">
                        <p class="text-sm font-semibold text-gray-700">Teacher</p>
                        <p class="text-[10px] text-gray-400">teacher@ut.com</p>
                    </div>
                </div>
                <i data-lucide="arrow-right"
                   class="w-4 h-4 text-gray-300 group-hover:text-emerald-500 transition-colors"></i>
            </button>
        </div>
    </div>
@endif

@endsection
