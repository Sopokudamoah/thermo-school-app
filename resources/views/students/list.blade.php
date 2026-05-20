@extends('layouts.app')

@section('page-title', 'Student List')

@section('content')

<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="font-heading text-xl font-bold text-gray-900">Student List</h1>
        <nav class="flex items-center gap-1.5 mt-1 text-sm text-gray-500">
            <a href="{{ route('home') }}" class="hover:text-indigo-600 transition-colors">Home</a>
            <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
            <span class="text-gray-900">Student List</span>
        </nav>
    </div>
    @if (!session()->has('browse_session_id') && Auth::user()->role == "admin")
    <a href="{{ route('student.create.show') }}"
       class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm px-4 py-2.5 rounded-lg transition-colors">
        <i data-lucide="user-plus" class="w-4 h-4"></i>
        Add Student
    </a>
    @endif
</div>

@include('session-messages')

{{-- Filter bar --}}
<div class="bg-white rounded-card shadow-card border border-gray-200 p-4 mb-5">
    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Filter by Class &amp; Section</p>
    <form class="flex flex-wrap items-end gap-3" action="{{ route('student.list.show') }}" method="GET">
        <div class="flex-1 min-w-[180px]">
            <label class="block text-xs font-medium text-gray-600 mb-1">Class</label>
            <select onchange="getSections(this);" name="class_id" required
                    class="block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm text-gray-900 bg-white focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                @isset($school_classes)
                    <option selected disabled>Please select a class</option>
                    @foreach ($school_classes as $school_class)
                        <option value="{{ $school_class->id }}" {{ ($school_class->id == request()->query('class_id')) ? 'selected' : '' }}>
                            {{ $school_class->class_name }}
                        </option>
                    @endforeach
                @endisset
            </select>
        </div>
        <div class="flex-1 min-w-[180px]">
            <label class="block text-xs font-medium text-gray-600 mb-1">Section</label>
            <select id="section-select" name="section_id" required
                    class="block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm text-gray-900 bg-white focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                <option value="{{ request()->query('section_id') }}">{{ request()->query('section_name') }}</option>
            </select>
        </div>
        <button type="submit"
                class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm px-4 py-2.5 rounded-lg transition-colors shrink-0">
            <i data-lucide="filter" class="w-4 h-4"></i>
            Load List
        </button>
    </form>
</div>

{{-- Section label --}}
@foreach ($studentList as $student)
    @if ($loop->first)
    <p class="text-sm text-gray-600 mb-3">
        <span class="font-semibold text-gray-900">Section:</span> {{ $student->section->section_name }}
    </p>
    @break
    @endif
@endforeach

{{-- Table --}}
<div class="bg-white rounded-card shadow-card border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm data-table">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200">
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">ID Card</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Student</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Phone</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($studentList as $student)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-4 py-3 font-mono text-xs text-gray-600">{{ $student->id_card_number }}</td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            @if (isset($student->student->photo))
                                <img src="{{ asset('/storage'.$student->student->photo) }}"
                                     class="w-8 h-8 rounded-full object-cover shrink-0" alt="Profile picture">
                            @else
                                <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center shrink-0">
                                    <i data-lucide="user" class="w-4 h-4 text-indigo-500"></i>
                                </div>
                            @endif
                            <span class="font-medium text-gray-900">{{ $student->student->first_name }} {{ $student->student->last_name }}</span>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-gray-600">{{ $student->student->email }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $student->student->phone }}</td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-end gap-1">
                            <a href="{{ route('student.attendance.show', ['id' => $student->student->id]) }}"
                               title="Attendance"
                               class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 transition-colors border border-gray-200">
                                <i data-lucide="calendar-check" class="w-3.5 h-3.5"></i> Attendance
                            </a>
                            <a href="{{ url('students/view/profile/'.$student->student->id) }}"
                               title="Profile"
                               class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 transition-colors border border-gray-200">
                                <i data-lucide="eye" class="w-3.5 h-3.5"></i> Profile
                            </a>
                            @can('edit users')
                            <a href="{{ route('student.edit.show', ['id' => $student->student->id]) }}"
                               title="Edit"
                               class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 transition-colors border border-gray-200">
                                <i data-lucide="pencil" class="w-3.5 h-3.5"></i> Edit
                            </a>
                            @endcan
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-4 py-12 text-center">
                        <div class="flex flex-col items-center gap-2">
                            <i data-lucide="users" class="w-8 h-8 text-gray-300"></i>
                            <p class="text-sm text-gray-500">No students found for the selected class and section.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script>
    function getSections(obj) {
        var class_id = obj.options[obj.selectedIndex].value;
        var url = "{{ route('get.sections.courses.by.classId') }}?class_id=" + class_id;

        fetch(url)
        .then((resp) => resp.json())
        .then(function(data) {
            var sectionSelect = document.getElementById('section-select');
            sectionSelect.options.length = 0;
            data.sections.unshift({'id': 0, 'section_name': 'Please select a section'});
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

@endsection
