@extends('layouts.app')

@section('page-title', 'Edit Vendor')

@section('content')

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="font-heading text-xl font-bold text-gray-900">Edit Vendor: {{ $vendor->name }}</h1>
            <nav class="flex items-center gap-1.5 mt-1 text-sm text-gray-500">
                <a href="{{ route('home') }}" class="hover:text-indigo-600 transition-colors">Home</a>
                <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                <a href="{{ route('finance.dashboard') }}" class="hover:text-indigo-600 transition-colors">Finance</a>
                <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                <a href="{{ route('finance.vendors.index') }}"
                   class="hover:text-indigo-600 transition-colors">Vendors</a>
                <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                <span class="text-gray-900">Edit Vendor</span>
            </nav>
        </div>
    </div>

    <div class="max-w-2xl bg-white rounded-card shadow-card border border-gray-200 overflow-hidden">
        <form action="{{ route('finance.vendors.update', $vendor->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Vendor Name <sup
                            class="text-red-500">*</sup></label>
                    <input type="text" name="name" required
                           class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm @error('name') border-red-500 @enderror"
                           value="{{ old('name', $vendor->name) }}">
                    @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Contact Person</label>
                        <input type="text" name="contact_person"
                               class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm"
                               value="{{ old('contact_person', $vendor->contact_person) }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                        <input type="text" name="phone"
                               class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm"
                               value="{{ old('phone', $vendor->phone) }}">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email"
                           class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm"
                           value="{{ old('email', $vendor->email) }}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                    <textarea name="address" rows="3"
                              class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">{{ old('address', $vendor->address) }}</textarea>
                </div>
                <div class="flex items-center">
                    <input id="active" name="active" type="checkbox" value="1"
                           {{ $vendor->active ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="active" class="ml-2 block text-sm text-gray-700">Active</label>
                </div>
            </div>
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end gap-3">
                <a href="{{ route('finance.vendors.index') }}"
                   class="px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg">Cancel</a>
                <button type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow-sm">
                    Update Vendor
                </button>
            </div>
        </form>
    </div>

@endsection
