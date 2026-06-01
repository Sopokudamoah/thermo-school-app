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

{{-- Table --}}
<div class="bg-white rounded-card shadow-card border border-gray-200 p-4">
    <div class="overflow-x-auto">
        {{ $dataTable->table(['class' => 'w-full text-sm']) }}
    </div>
</div>

@push('scripts')
    {{ $dataTable->scripts() }}
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
