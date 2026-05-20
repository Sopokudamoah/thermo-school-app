@extends('layouts.app')
@section('page-title', 'Teacher Profile')

@section('content')
<div class="mb-6">
    <h1 class="font-heading text-xl font-bold text-gray-900">
        <i data-lucide="users" class="inline w-5 h-5 mr-1"></i> Teacher
    </h1>
    <nav class="flex items-center gap-1.5 mt-1 text-sm text-gray-500">
        <a href="{{route('home')}}" class="hover:text-indigo-600">Home</a>
        <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
        <a href="{{route('teacher.list.show')}}" class="hover:text-indigo-600">Teacher List</a>
        <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
        <span class="text-gray-900">Profile</span>
    </nav>
</div>

<div class="flex flex-col lg:flex-row gap-6">
    {{-- Sidebar card --}}
    <div class="w-full lg:w-64 shrink-0">
        <div class="bg-white rounded-card shadow-card border border-gray-200 overflow-hidden">
            <div class="px-8 pt-6 pb-2">
                @if (isset($teacher->photo))
                    <img src="{{asset('/storage'.$teacher->photo)}}" class="rounded-xl w-full object-cover" alt="Profile photo">
                @else
                    <img src="{{asset('imgs/profile.png')}}" class="rounded-xl w-full object-cover" alt="Profile photo">
                @endif
            </div>
            <div class="p-4">
                <h5 class="font-heading font-semibold text-gray-900 text-base">{{$teacher->first_name}} {{$teacher->last_name}}</h5>
            </div>
            <div class="border-t border-gray-100 divide-y divide-gray-100">
                <div class="px-4 py-2.5 text-sm text-gray-700">Gender: {{$teacher->gender}}</div>
                <div class="px-4 py-2.5 text-sm text-gray-700">Phone: {{$teacher->phone}}</div>
            </div>
        </div>
    </div>

    {{-- Detail panel --}}
    <div class="flex-1">
        <div class="bg-white rounded-card shadow-card border border-gray-200 p-6">
            <h3 class="font-heading text-sm font-semibold text-gray-700 uppercase tracking-wider mb-4 pb-2 border-b border-gray-100">Teacher Information</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-3">
                <div class="flex items-start">
                    <span class="text-xs font-medium text-gray-500 uppercase w-36 shrink-0">First Name</span>
                    <span class="text-sm text-gray-900">{{$teacher->first_name}}</span>
                </div>
                <div class="flex items-start">
                    <span class="text-xs font-medium text-gray-500 uppercase w-36 shrink-0">Last Name</span>
                    <span class="text-sm text-gray-900">{{$teacher->last_name}}</span>
                </div>
                <div class="flex items-start">
                    <span class="text-xs font-medium text-gray-500 uppercase w-36 shrink-0">Email</span>
                    <span class="text-sm text-gray-900">{{$teacher->email}}</span>
                </div>
                <div class="flex items-start">
                    <span class="text-xs font-medium text-gray-500 uppercase w-36 shrink-0">Nationality</span>
                    <span class="text-sm text-gray-900">{{$teacher->nationality}}</span>
                </div>
                <div class="flex items-start">
                    <span class="text-xs font-medium text-gray-500 uppercase w-36 shrink-0">Address</span>
                    <span class="text-sm text-gray-900">{{$teacher->address}}</span>
                </div>
                <div class="flex items-start">
                    <span class="text-xs font-medium text-gray-500 uppercase w-36 shrink-0">Address 2</span>
                    <span class="text-sm text-gray-900">{{$teacher->address2}}</span>
                </div>
                <div class="flex items-start">
                    <span class="text-xs font-medium text-gray-500 uppercase w-36 shrink-0">City</span>
                    <span class="text-sm text-gray-900">{{$teacher->city}}</span>
                </div>
                <div class="flex items-start">
                    <span class="text-xs font-medium text-gray-500 uppercase w-36 shrink-0">Zip</span>
                    <span class="text-sm text-gray-900">{{$teacher->zip}}</span>
                </div>
                <div class="flex items-start">
                    <span class="text-xs font-medium text-gray-500 uppercase w-36 shrink-0">Phone</span>
                    <span class="text-sm text-gray-900">{{$teacher->phone}}</span>
                </div>
                <div class="flex items-start">
                    <span class="text-xs font-medium text-gray-500 uppercase w-36 shrink-0">Gender</span>
                    <span class="text-sm text-gray-900">{{$teacher->gender}}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
