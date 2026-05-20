@extends('layouts.app')

@section('page-title', 'Register')

@section('content')

<h2 class="font-heading text-2xl font-bold text-gray-900 mb-1">{{ __('Create an account') }}</h2>
<p class="text-sm text-gray-500 mb-7">{{ __('Fill in the details below to register.') }}</p>

<form method="POST" action="{{ route('register') }}" class="space-y-5">
    @csrf

    <div>
        <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('Name') }}</label>
        <input id="name" type="text" name="name" value="{{ old('name') }}"
               required autocomplete="name" autofocus
               placeholder="Full name"
               class="block w-full rounded-lg border px-3.5 py-2.5 text-sm text-gray-900 placeholder-gray-400 transition-colors
                      @error('name') border-red-400 ring-2 ring-red-100 @else border-gray-300 @enderror
                      focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
        @error('name')
        <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
            <i data-lucide="alert-circle" class="w-3.5 h-3.5"></i> {{ $message }}
        </p>
        @enderror
    </div>

    <div>
        <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('E-Mail Address') }}</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}"
               required autocomplete="email"
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
               required autocomplete="new-password"
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

    <div>
        <label for="password-confirm" class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('Confirm Password') }}</label>
        <input id="password-confirm" type="password" name="password_confirmation"
               required autocomplete="new-password"
               placeholder="••••••••"
               class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 placeholder-gray-400 transition-colors
                      focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
    </div>

    <button type="submit"
            class="w-full bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white font-medium text-sm py-2.5 rounded-lg transition-colors">
        {{ __('Register') }}
    </button>
</form>

@endsection
