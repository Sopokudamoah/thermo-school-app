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

@endsection
