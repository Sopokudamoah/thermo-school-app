@extends('layouts.app')

@section('page-title', 'Confirm Password')

@section('content')

<h2 class="font-heading text-2xl font-bold text-gray-900 mb-1">{{ __('Confirm Password') }}</h2>
<p class="text-sm text-gray-500 mb-7">{{ __('Please confirm your password before continuing.') }}</p>

<form method="POST" action="{{ route('password.confirm') }}" class="space-y-5">
    @csrf

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

    <div class="flex items-center gap-3">
        <button type="submit"
                class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm px-4 py-2.5 rounded-lg transition-colors">
            {{ __('Confirm Password') }}
        </button>

        @if (Route::has('password.request'))
        <a href="{{ route('password.request') }}" class="text-sm text-indigo-600 hover:text-indigo-700 hover:underline">
            {{ __('Forgot Your Password?') }}
        </a>
        @endif
    </div>
</form>

@endsection
