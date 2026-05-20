@extends('layouts.app')

@section('page-title', 'Change Password')

@section('content')

<div class="mb-6">
    <h1 class="font-heading text-xl font-bold text-gray-900">Change Password</h1>
    <nav class="flex items-center gap-1.5 mt-1 text-sm text-gray-500">
        <a href="{{ route('home') }}" class="hover:text-indigo-600 transition-colors">Home</a>
        <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
        <span class="text-gray-900">Change Password</span>
    </nav>
</div>

@include('session-messages')

<div class="bg-white rounded-card shadow-card border border-gray-200 p-6 max-w-lg">
    <form action="{{ route('password.update') }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label for="old-password" class="block text-sm font-medium text-gray-700 mb-1.5">Old Password</label>
            <input class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 transition-colors focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                   id="old-password" name="old_password" type="password" placeholder="Current password">
        </div>

        <div>
            <label for="new-password" class="block text-sm font-medium text-gray-700 mb-1.5">New Password</label>
            <input class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 transition-colors focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                   id="new-password" name="new_password" type="password" placeholder="New password">
        </div>

        <div>
            <label for="confirm-password" class="block text-sm font-medium text-gray-700 mb-1.5">Confirm New Password</label>
            <input class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 transition-colors focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                   id="confirm-password" name="new_password_confirmation" type="password" placeholder="Confirm new password">
        </div>

        <div class="pt-2">
            <button type="submit"
                    class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm px-5 py-2.5 rounded-lg transition-colors">
                <i data-lucide="check" class="w-4 h-4"></i>
                Save Password
            </button>
        </div>
    </form>
</div>

@endsection
