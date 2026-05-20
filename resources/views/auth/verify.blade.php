@extends('layouts.app')

@section('page-title', 'Verify Email Address')

@section('content')

<h2 class="font-heading text-2xl font-bold text-gray-900 mb-1">{{ __('Verify Your Email Address') }}</h2>
<p class="text-sm text-gray-500 mb-7">{{ __('Before proceeding, please check your email for a verification link.') }}</p>

@if (session('resent'))
<div class="flex items-start gap-2.5 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-lg px-4 py-3 mb-5 text-sm">
    <i data-lucide="check-circle" class="w-4 h-4 shrink-0 mt-0.5"></i>
    <span>{{ __('A fresh verification link has been sent to your email address.') }}</span>
</div>
@endif

<p class="text-sm text-gray-600">
    {{ __('If you did not receive the email') }},
    <form class="inline" method="POST" action="{{ route('verification.resend') }}">
        @csrf
        <button type="submit" class="text-indigo-600 hover:text-indigo-700 hover:underline text-sm font-medium">
            {{ __('click here to request another') }}
        </button>.
    </form>
</p>

@endsection
