@extends('layouts.app')

@section('page-title', 'Reset Password')

@section('content')

<a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 mb-6">
    <i data-lucide="arrow-left" class="w-4 h-4"></i> Back to login
</a>

<h2 class="font-heading text-2xl font-bold text-gray-900 mb-1">Reset your password</h2>
<p class="text-sm text-gray-500 mb-7">Enter your email and we'll send a password reset link.</p>

@if (session('status'))
<div class="flex items-start gap-2.5 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-lg px-4 py-3 mb-5 text-sm">
    <i data-lucide="check-circle" class="w-4 h-4 shrink-0 mt-0.5"></i>
    <span>{{ session('status') }}</span>
</div>
@endif

<form method="POST" action="{{ route('password.email') }}" class="space-y-5">
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

    <button type="submit"
            class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm py-2.5 rounded-lg transition-colors">
        {{ __('Send Password Reset Link') }}
    </button>
</form>

@endsection
