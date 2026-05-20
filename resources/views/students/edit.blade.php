@extends('layouts.app')
@section('page-title', 'Edit Student')

@section('content')
<div class="mb-6">
    <h1 class="font-heading text-xl font-bold text-gray-900">
        <i data-lucide="users" class="inline w-5 h-5 mr-1"></i> Edit Student
    </h1>
    <nav class="flex items-center gap-1.5 mt-1 text-sm text-gray-500">
        <a href="{{route('home')}}" class="hover:text-indigo-600">Home</a>
        <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
        <a href="{{url()->previous()}}" class="hover:text-indigo-600">Student List</a>
        <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
        <span class="text-gray-900">Edit Student</span>
    </nav>
</div>

@include('session-messages')

<div class="bg-white rounded-card shadow-card border border-gray-200 p-6">
    <form action="{{route('school.student.update')}}" method="POST">
        @csrf
        <input type="hidden" name="student_id" value="{{$student->id}}">

        <h3 class="font-heading text-sm font-semibold text-gray-700 uppercase tracking-wider mb-4 pb-2 border-b border-gray-100">Personal Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div>
                <label for="inputFirstName" class="block text-sm font-medium text-gray-700 mb-1.5">First Name <sup class="text-indigo-500">*</sup></label>
                <input type="text" id="inputFirstName" name="first_name" placeholder="First Name" required value="{{$student->first_name}}"
                    class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-colors">
            </div>
            <div>
                <label for="inputLastName" class="block text-sm font-medium text-gray-700 mb-1.5">Last Name <sup class="text-indigo-500">*</sup></label>
                <input type="text" id="inputLastName" name="last_name" placeholder="Last Name" required value="{{$student->last_name}}"
                    class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-colors">
            </div>
            <div>
                <label for="inputEmail4" class="block text-sm font-medium text-gray-700 mb-1.5">Email <sup class="text-indigo-500">*</sup></label>
                <input type="email" id="inputEmail4" name="email" required value="{{$student->email}}"
                    class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-colors">
            </div>
            <div>
                <label for="inputBirthday" class="block text-sm font-medium text-gray-700 mb-1.5">Birthday <sup class="text-indigo-500">*</sup></label>
                <input type="date" id="inputBirthday" name="birthday" placeholder="Birthday" required value="{{$student->birthday}}"
                    class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-colors">
            </div>
            <div>
                <label for="inputAddress" class="block text-sm font-medium text-gray-700 mb-1.5">Address <sup class="text-indigo-500">*</sup></label>
                <input type="text" id="inputAddress" name="address" placeholder="634 Main St" required value="{{$student->address}}"
                    class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-colors">
            </div>
            <div>
                <label for="inputAddress2" class="block text-sm font-medium text-gray-700 mb-1.5">Address 2</label>
                <input type="text" id="inputAddress2" name="address2" placeholder="Apartment, studio, or floor" value="{{$student->address2}}"
                    class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-colors">
            </div>
            <div>
                <label for="inputCity" class="block text-sm font-medium text-gray-700 mb-1.5">City <sup class="text-indigo-500">*</sup></label>
                <input type="text" id="inputCity" name="city" placeholder="Dhaka..." required value="{{$student->city}}"
                    class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-colors">
            </div>
            <div>
                <label for="inputZip" class="block text-sm font-medium text-gray-700 mb-1.5">Zip <sup class="text-indigo-500">*</sup></label>
                <input type="text" id="inputZip" name="zip" required value="{{$student->zip}}"
                    class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-colors">
            </div>
            <div>
                <label for="inputState" class="block text-sm font-medium text-gray-700 mb-1.5">Gender <sup class="text-indigo-500">*</sup></label>
                <select id="inputState" name="gender" required
                    class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 bg-white focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                    <option value="Male" {{($student->gender == 'Male')?'selected':null}}>Male</option>
                    <option value="Female" {{($student->gender == 'Female')?'selected':null}}>Female</option>
                </select>
            </div>
            <div>
                <label for="inputNationality" class="block text-sm font-medium text-gray-700 mb-1.5">Nationality <sup class="text-indigo-500">*</sup></label>
                <input type="text" id="inputNationality" name="nationality" placeholder="e.g. Bangladeshi, German, ..." required value="{{$student->nationality}}"
                    class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-colors">
            </div>
            <div>
                <label for="inputBloodType" class="block text-sm font-medium text-gray-700 mb-1.5">Blood Type <sup class="text-indigo-500">*</sup></label>
                <select id="inputBloodType" name="blood_type" required
                    class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 bg-white focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                    <option value="A+" {{($student->blood_type == 'A+')?'selected':null}}>A+</option>
                    <option value="A-" {{($student->blood_type == 'A-')?'selected':null}}>A-</option>
                    <option value="B+" {{($student->blood_type == 'B+')?'selected':null}}>B+</option>
                    <option value="B-" {{($student->blood_type == 'B-')?'selected':null}}>B-</option>
                    <option value="O+" {{($student->blood_type == 'O+')?'selected':null}}>O+</option>
                    <option value="O-" {{($student->blood_type == 'O-')?'selected':null}}>O-</option>
                    <option value="AB+" {{($student->blood_type == 'AB+')?'selected':null}}>AB+</option>
                    <option value="AB-" {{($student->blood_type == 'AB-')?'selected':null}}>AB-</option>
                    <option value="Other" {{($student->blood_type == 'Other')?'selected':null}}>Other</option>
                </select>
            </div>
            <div>
                <label for="inputReligion" class="block text-sm font-medium text-gray-700 mb-1.5">Religion <sup class="text-indigo-500">*</sup></label>
                <select id="inputReligion" name="religion" required
                    class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 bg-white focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                    <option {{($student->religion == 'Islam')?'selected':null}}>Islam</option>
                    <option {{($student->religion == 'Hinduism')?'selected':null}}>Hinduism</option>
                    <option {{($student->religion == 'Christianity')?'selected':null}}>Christianity</option>
                    <option {{($student->religion == 'Buddhism')?'selected':null}}>Buddhism</option>
                    <option {{($student->religion == 'Judaism')?'selected':null}}>Judaism</option>
                    <option {{($student->religion == 'Other')?'selected':null}}>Other</option>
                </select>
            </div>
            <div>
                <label for="inputPhone" class="block text-sm font-medium text-gray-700 mb-1.5">Phone <sup class="text-indigo-500">*</sup></label>
                <input type="text" id="inputPhone" name="phone" placeholder="+880 01......" required value="{{$student->phone}}"
                    class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-colors">
            </div>
            <div class="lg:col-span-2">
                <label for="inputIdCardNumber" class="block text-sm font-medium text-gray-700 mb-1.5">Id Card Number <sup class="text-indigo-500">*</sup></label>
                <input type="text" id="inputIdCardNumber" name="id_card_number" placeholder="e.g. 2021-03-01-02-01 (Year Semester Class Section Roll)" required value="{{$promotion_info->id_card_number}}"
                    class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-colors">
            </div>
        </div>

        <h3 class="font-heading text-sm font-semibold text-gray-700 uppercase tracking-wider mb-4 pb-2 border-b border-gray-100">Parents' Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div>
                <label for="inputFatherName" class="block text-sm font-medium text-gray-700 mb-1.5">Father Name <sup class="text-indigo-500">*</sup></label>
                <input type="text" id="inputFatherName" name="father_name" placeholder="Father Name" required value="{{$parent_info->father_name}}"
                    class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-colors">
            </div>
            <div>
                <label for="inputFatherPhone" class="block text-sm font-medium text-gray-700 mb-1.5">Father's Phone <sup class="text-indigo-500">*</sup></label>
                <input type="text" id="inputFatherPhone" name="father_phone" placeholder="+880 01......" required value="{{$parent_info->father_phone}}"
                    class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-colors">
            </div>
            <div>
                <label for="inputMotherName" class="block text-sm font-medium text-gray-700 mb-1.5">Mother Name <sup class="text-indigo-500">*</sup></label>
                <input type="text" id="inputMotherName" name="mother_name" placeholder="Mother Name" required value="{{$parent_info->mother_name}}"
                    class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-colors">
            </div>
            <div>
                <label for="inputMotherPhone" class="block text-sm font-medium text-gray-700 mb-1.5">Mother's Phone <sup class="text-indigo-500">*</sup></label>
                <input type="text" id="inputMotherPhone" name="mother_phone" placeholder="+880 01......" required value="{{$parent_info->mother_phone}}"
                    class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-colors">
            </div>
            <div class="lg:col-span-2">
                <label for="inputParentAddress" class="block text-sm font-medium text-gray-700 mb-1.5">Address <sup class="text-indigo-500">*</sup></label>
                <input type="text" id="inputParentAddress" name="parent_address" placeholder="634 Main St" required value="{{$parent_info->parent_address}}"
                    class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-colors">
            </div>
        </div>

        <div>
            <button type="submit" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm px-5 py-2.5 rounded-lg transition-colors">
                <i data-lucide="check" class="w-4 h-4"></i> Update
            </button>
        </div>
    </form>
</div>
@include('components.photos.photo-input')
@endsection
