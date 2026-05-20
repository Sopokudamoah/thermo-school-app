@extends('layouts.app')
@section('page-title', 'Promote Students')

@section('content')
<div class="mb-6">
    <h1 class="font-heading text-xl font-bold text-gray-900"><i data-lucide="users" class="inline w-5 h-5 mr-2"></i> Promote Students</h1>
</div>

@include('session-messages')

<div class="flex items-start gap-2 text-red-600 text-sm mb-4">
    <i data-lucide="alert-circle" class="w-4 h-4 flex-shrink-0 mt-0.5"></i>
    <span>Students must be promoted only once to a new Session. Usually, Admin will create a New Session once Academic activity ends for the Current Session.</span>
</div>

<form action="{{route('promotions.store')}}" method="POST">
    @csrf
    <div class="bg-white rounded-card shadow-card border border-gray-200 overflow-x-auto mb-4">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200">
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">#ID Card Number</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">First Name</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Last Name</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Previous Class</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Previous Section</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Promoting to Class</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Promoting to Section</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @isset($students)
                    @foreach ($students as $index => $student)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-3">
                            <input type="text" class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100" name="id_card_number[{{$student->student->id}}]" value="{{$student->id_card_number}}">
                        </td>
                        <td class="px-4 py-3 text-gray-600">{{$student->student->first_name}}</td>
                        <td class="px-4 py-3 text-gray-600">{{$student->student->last_name}}</td>
                        <td class="px-4 py-3 text-gray-600">{{$schoolClass->class_name}}</td>
                        <td class="px-4 py-3 text-gray-600">{{$section->section_name}}</td>
                        <td class="px-4 py-3">
                            <select onchange="getSections(this, {{$index}});" class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 bg-white" id="inputAssignToClass{{$index}}" name="class_id[{{$index}}]" required>
                                @isset($school_classes)
                                    <option selected disabled>Please select a class</option>
                                    @foreach ($school_classes as $school_class)
                                        <option value="{{$school_class->id}}">{{$school_class->class_name}}</option>
                                    @endforeach
                                @endisset
                            </select>
                        </td>
                        <td class="px-4 py-3">
                            <select class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 bg-white" aria-label="Section" id="inputAssignToSection{{$index}}" name="section_id[{{$index}}]" required>
                            </select>
                        </td>
                    </tr>
                    @endforeach
                @endisset
            </tbody>
        </table>
    </div>
    <div>
        <button type="submit" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm px-4 py-2.5 rounded-lg transition-colors">
            <i data-lucide="arrow-up-01" class="w-4 h-4"></i> Promote
        </button>
    </div>
</form>
@endsection

@push('scripts')
<script>
    function getSections(obj, index) {
        var class_id = obj.options[obj.selectedIndex].value;

        var url = "{{route('get.sections.courses.by.classId')}}?class_id=" + class_id

        fetch(url)
        .then((resp) => resp.json())
        .then(function(data) {
            var sectionSelect = document.getElementById('inputAssignToSection'+index);
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
