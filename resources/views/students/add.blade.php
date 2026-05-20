@extends('layouts.app')
@section('page-title', 'Add Student')

@section('content')
<div class="mb-6">
    <h1 class="font-heading text-xl font-bold text-gray-900">
        <i data-lucide="users" class="inline w-5 h-5 mr-1"></i> Add Student
    </h1>
    <nav class="flex items-center gap-1.5 mt-1 text-sm text-gray-500">
        <a href="{{route('home')}}" class="hover:text-indigo-600">Home</a>
        <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
        <span class="text-gray-900">Add Student</span>
    </nav>
</div>

@include('session-messages')

<p class="flex items-center gap-2 text-sm text-indigo-600 mb-4">
    <i data-lucide="alert-circle" class="w-4 h-4 shrink-0"></i>
    Remember to create related "Class" and "Section" before adding student
</p>

<div class="bg-white rounded-card shadow-card border border-gray-200 p-6">
    <form action="{{route('school.student.create')}}" method="POST">
        @csrf

        <h3 class="font-heading text-sm font-semibold text-gray-700 uppercase tracking-wider mb-4 pb-2 border-b border-gray-100">Personal Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
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
                <label for="inputEmail4" class="block text-sm font-medium text-gray-700 mb-1.5">Email <sup class="text-indigo-500">*</sup></label>
                <input type="email" id="inputEmail4" name="email" required
                    class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-colors">
            </div>
            <div>
                <label for="inputPassword4" class="block text-sm font-medium text-gray-700 mb-1.5">Password <sup class="text-indigo-500">*</sup></label>
                <input type="password" id="inputPassword4" name="password" required
                    class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-colors">
            </div>
            <div>
                <label for="formFile" class="block text-sm font-medium text-gray-700 mb-1.5">Photo</label>
                <input type="file" id="formFile" onchange="previewFile()"
                    class="block w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100">
                <div id="previewPhoto" class="mt-2"></div>
                <input type="hidden" id="photoHiddenInput" name="photo" value="">
            </div>
            <div>
                <label for="inputBirthday" class="block text-sm font-medium text-gray-700 mb-1.5">Birthday <sup class="text-indigo-500">*</sup></label>
                <input type="date" id="inputBirthday" name="birthday" placeholder="Birthday" required
                    class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-colors">
            </div>
            <div>
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
                <label for="inputState" class="block text-sm font-medium text-gray-700 mb-1.5">Gender <sup class="text-indigo-500">*</sup></label>
                <select id="inputState" name="gender" required
                    class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 bg-white focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                    <option value="Male" selected>Male</option>
                    <option value="Female">Female</option>
                </select>
            </div>
            <div>
                <label for="inputNationality" class="block text-sm font-medium text-gray-700 mb-1.5">Nationality <sup class="text-indigo-500">*</sup></label>
                <input type="text" id="inputNationality" name="nationality" placeholder="e.g. Bangladeshi, German, ..." required
                    class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-colors">
            </div>
            <div>
                <label for="inputBloodType" class="block text-sm font-medium text-gray-700 mb-1.5">Blood Type <sup class="text-indigo-500">*</sup></label>
                <select id="inputBloodType" name="blood_type" required
                    class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 bg-white focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                    <option selected>A+</option>
                    <option>A-</option>
                    <option>B+</option>
                    <option>B-</option>
                    <option>O+</option>
                    <option>O-</option>
                    <option>AB+</option>
                    <option>AB-</option>
                    <option>Other</option>
                </select>
            </div>
            <div>
                <label for="inputReligion" class="block text-sm font-medium text-gray-700 mb-1.5">Religion <sup class="text-indigo-500">*</sup></label>
                <select id="inputReligion" name="religion" required
                    class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 bg-white focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                    <option selected>Islam</option>
                    <option>Hinduism</option>
                    <option>Christianity</option>
                    <option>Buddhism</option>
                    <option>Judaism</option>
                    <option>Other</option>
                </select>
            </div>
            <div>
                <label for="inputPhone" class="block text-sm font-medium text-gray-700 mb-1.5">Phone <sup class="text-indigo-500">*</sup></label>
                <input type="text" id="inputPhone" name="phone" placeholder="+880 01......" required
                    class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-colors">
            </div>
            <div class="lg:col-span-2">
                <label for="inputIdCardNumber" class="block text-sm font-medium text-gray-700 mb-1.5">Id Card Number <sup class="text-indigo-500">*</sup></label>
                <input type="text" id="inputIdCardNumber" name="id_card_number" placeholder="e.g. 2021-03-01-02-01 (Year Semester Class Section Roll)" required
                    class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-colors">
            </div>
        </div>

        <h3 class="font-heading text-sm font-semibold text-gray-700 uppercase tracking-wider mb-4 pb-2 border-b border-gray-100">Parents' Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div>
                <label for="inputFatherName" class="block text-sm font-medium text-gray-700 mb-1.5">Father Name <sup class="text-indigo-500">*</sup></label>
                <input type="text" id="inputFatherName" name="father_name" placeholder="Father Name" required
                    class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-colors">
            </div>
            <div>
                <label for="inputFatherPhone" class="block text-sm font-medium text-gray-700 mb-1.5">Father's Phone <sup class="text-indigo-500">*</sup></label>
                <input type="text" id="inputFatherPhone" name="father_phone" placeholder="+880 01......" required
                    class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-colors">
            </div>
            <div>
                <label for="inputMotherName" class="block text-sm font-medium text-gray-700 mb-1.5">Mother Name <sup class="text-indigo-500">*</sup></label>
                <input type="text" id="inputMotherName" name="mother_name" placeholder="Mother Name" required
                    class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-colors">
            </div>
            <div>
                <label for="inputMotherPhone" class="block text-sm font-medium text-gray-700 mb-1.5">Mother's Phone <sup class="text-indigo-500">*</sup></label>
                <input type="text" id="inputMotherPhone" name="mother_phone" placeholder="+880 01......" required
                    class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-colors">
            </div>
            <div class="lg:col-span-2">
                <label for="inputParentAddress" class="block text-sm font-medium text-gray-700 mb-1.5">Address <sup class="text-indigo-500">*</sup></label>
                <input type="text" id="inputParentAddress" name="parent_address" placeholder="634 Main St" required
                    class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-colors">
            </div>
        </div>

        <h3 class="font-heading text-sm font-semibold text-gray-700 uppercase tracking-wider mb-4 pb-2 border-b border-gray-100">Academic Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
            <div>
                <label for="inputAssignToClass" class="block text-sm font-medium text-gray-700 mb-1.5">Assign to class <sup class="text-indigo-500">*</sup></label>
                <select onchange="getSections(this);" id="inputAssignToClass" name="class_id" required
                    class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 bg-white focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                    @isset($school_classes)
                        <option selected disabled>Please select a class</option>
                        @foreach ($school_classes as $school_class)
                            <option value="{{$school_class->id}}">{{$school_class->class_name}}</option>
                        @endforeach
                    @endisset
                </select>
            </div>
            <div>
                <label for="inputAssignToSection" class="block text-sm font-medium text-gray-700 mb-1.5">Assign to section <sup class="text-indigo-500">*</sup></label>
                <select id="inputAssignToSection" name="section_id" required
                    class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 bg-white focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                </select>
            </div>
            <div>
                <label for="inputBoardRegistrationNumber" class="block text-sm font-medium text-gray-700 mb-1.5">Board Registration No.</label>
                <input type="text" id="inputBoardRegistrationNumber" name="board_reg_no" placeholder="Registration No."
                    class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-colors">
            </div>
            <input type="hidden" name="session_id" value="{{$current_school_session_id}}">
        </div>

        <div>
            <button type="submit" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm px-5 py-2.5 rounded-lg transition-colors">
                <i data-lucide="user-plus" class="w-4 h-4"></i> Add
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    function getSections(obj) {
        var class_id = obj.options[obj.selectedIndex].value;
        var url = "{{route('get.sections.courses.by.classId')}}?class_id=" + class_id
        fetch(url)
        .then((resp) => resp.json())
        .then(function(data) {
            var sectionSelect = document.getElementById('inputAssignToSection');
            sectionSelect.options.length = 0;
            data.sections.unshift({'id': 0,'section_name': 'Please select a section'})
            data.sections.forEach(function(section, key) {
                sectionSelect[key] = new Option(section.section_name, section.id);
            });
        })
        .catch(function(error) {
            console.log(error);
        });
    }
</script>
@endpush
@include('components.photos.photo-input')
@endsection
