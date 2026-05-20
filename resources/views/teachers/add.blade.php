@extends('layouts.app')
@section('page-title', 'Add Teacher')

@section('content')
<div class="mb-6">
    <h1 class="font-heading text-xl font-bold text-gray-900">
        <i data-lucide="users" class="inline w-5 h-5 mr-1"></i> Add Teacher
    </h1>
    <nav class="flex items-center gap-1.5 mt-1 text-sm text-gray-500">
        <a href="{{route('home')}}" class="hover:text-indigo-600">Home</a>
        <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
        <span class="text-gray-900">Add Teacher</span>
    </nav>
</div>

@include('session-messages')

<div class="bg-white rounded-card shadow-card border border-gray-200 p-6">
    <form action="{{route('school.teacher.create')}}" method="POST">
        @csrf

        <h3 class="font-heading text-sm font-semibold text-gray-700 uppercase tracking-wider mb-4 pb-2 border-b border-gray-100">Teacher Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
            <div>
                <label for="inputFirstName" class="block text-sm font-medium text-gray-700 mb-1.5">First Name <sup class="text-indigo-500">*</sup></label>
                <input type="text" id="inputFirstName" name="first_name" placeholder="First Name" required
                    class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-colors">
            </div>
            <div>
                <label for="inputLastName" class="block text-sm font-medium text-gray-700 mb-1.5">Last Name <sup class="text-indigo-500">*</sup></label>
                <input type="text" id="inputLastName" name="last_name" placeholder="Last Name" required
                    class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-colors">
            </div>
            <div>
                <label for="inputEmail" class="block text-sm font-medium text-gray-700 mb-1.5">Email <sup class="text-indigo-500">*</sup></label>
                <input type="email" id="inputEmail" name="email" required
                    class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-colors">
            </div>
            <div>
                <label for="inputPassword" class="block text-sm font-medium text-gray-700 mb-1.5">Password <sup class="text-indigo-500">*</sup></label>
                <input type="password" id="inputPassword" name="password" required
                    class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-colors">
            </div>
            <div>
                <label for="formFile" class="block text-sm font-medium text-gray-700 mb-1.5">Photo</label>
                <input type="file" id="formFile" onchange="previewFile()"
                    class="block w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100">
                <div id="previewPhoto" class="mt-2"></div>
                <input type="hidden" id="photoHiddenInput" name="photo" value="">
            </div>
            <div class="lg:col-span-2">
                <label for="inputAddress" class="block text-sm font-medium text-gray-700 mb-1.5">Address <sup class="text-indigo-500">*</sup></label>
                <input type="text" id="inputAddress" name="address" placeholder="634 Main St" required
                    class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-colors">
            </div>
            <div>
                <label for="inputAddress2" class="block text-sm font-medium text-gray-700 mb-1.5">Address 2</label>
                <input type="text" id="inputAddress2" name="address2" placeholder="Apartment, studio, or floor"
                    class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-colors">
            </div>
            <div>
                <label for="inputCity" class="block text-sm font-medium text-gray-700 mb-1.5">City <sup class="text-indigo-500">*</sup></label>
                <input type="text" id="inputCity" name="city" placeholder="Dhaka..." required
                    class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-colors">
            </div>
            <div>
                <label for="inputZip" class="block text-sm font-medium text-gray-700 mb-1.5">Zip <sup class="text-indigo-500">*</sup></label>
                <input type="text" id="inputZip" name="zip" required
                    class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-colors">
            </div>
            <div>
                <label for="inputPhone" class="block text-sm font-medium text-gray-700 mb-1.5">Phone <sup class="text-indigo-500">*</sup></label>
                <input type="text" id="inputPhone" name="phone" placeholder="+880 01......" required
                    class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-colors">
            </div>
            <div>
                <label for="inputGender" class="block text-sm font-medium text-gray-700 mb-1.5">Gender <sup class="text-indigo-500">*</sup></label>
                <select id="inputGender" name="gender" required
                    class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 bg-white focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                    <option selected>Male</option>
                    <option>Female</option>
                </select>
            </div>
            <div>
                <label for="inputNationality" class="block text-sm font-medium text-gray-700 mb-1.5">Nationality <sup class="text-indigo-500">*</sup></label>
                <input type="text" id="inputNationality" name="nationality" placeholder="e.g. Bangladeshi, German, ..." required
                    class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-colors">
            </div>
        </div>

        <div>
            <button type="submit" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm px-5 py-2.5 rounded-lg transition-colors">
                <i data-lucide="user-plus" class="w-4 h-4"></i> Add
            </button>
        </div>
    </form>
</div>

@include('components.photos.photo-input')
@endsection
