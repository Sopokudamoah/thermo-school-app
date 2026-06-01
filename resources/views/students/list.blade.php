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
    <div class="flex items-center gap-3">
        @if (!session()->has('browse_session_id') && Auth::user()->role == "admin")
            @can('create users')
                <button @click="$dispatch('open-modal', 'import-students')"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold text-indigo-600 bg-indigo-50 hover:bg-indigo-100 transition-colors border border-indigo-200 shadow-sm">
                    <i data-lucide="upload" class="w-4 h-4"></i> Import Students
                </button>
            @endcan
            <a href="{{ route('student.create.show') }}"
               class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm px-4 py-2.5 rounded-lg transition-colors">
                <i data-lucide="user-plus" class="w-4 h-4"></i>
                Add Student
            </a>
        @endif
    </div>
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

@push('modals')
    @can('create users')
        <div x-show="activeModal === 'import-students'"
             class="fixed inset-0 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="activeModal === 'import-students'"
                     x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" @click="closeModal()"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div x-show="activeModal === 'import-students'"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                    <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-900">Import Students</h3>
                        <button @click="closeModal()" class="text-gray-400 hover:text-gray-500">
                            <i data-lucide="x" class="w-5 h-5"></i>
                        </button>
                    </div>

                    <div class="mb-4">
                        <a href="{{ route('student.import.template') }}"
                           class="inline-flex items-center gap-1.5 text-sm font-medium text-indigo-600 hover:text-indigo-700">
                            <i data-lucide="download" class="w-4 h-4"></i> Download Template CSV
                        </a>
                    </div>

                    <form action="{{ route('student.import') }}" method="POST" enctype="multipart/form-data"
                          class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Select Class <sup
                                    class="text-indigo-500">*</sup></label>
                            <select name="class_id" required onchange="getSectionsModal(this)"
                                    class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 bg-white">
                                <option value="">Select Class</option>
                                @foreach($school_classes as $class)
                                    <option value="{{ $class->id }}">{{ $class->class_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Select Section <sup
                                    class="text-indigo-500">*</sup></label>
                            <select name="section_id" id="modal_section_id" required
                                    class="block w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 bg-white">
                                <option value="">Select Section</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">CSV File <sup
                                    class="text-indigo-500">*</sup></label>
                            <input type="file" name="student_file" required
                                   class="block w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100 border border-gray-300 rounded-lg">
                        </div>
                        <div class="mt-6 flex flex-col sm:flex-row-reverse gap-3">
                            <button type="submit"
                                    class="inline-flex justify-center items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-sm px-4 py-2.5 rounded-lg transition-colors">
                                <i data-lucide="check" class="w-4 h-4"></i> Import
                            </button>
                            <button type="button" @click="closeModal()"
                                    class="inline-flex justify-center items-center gap-2 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-semibold text-sm px-4 py-2.5 rounded-lg transition-colors">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endcan
@endpush

@push('scripts')
    {{ $dataTable->scripts() }}
<script>
    function getSectionsModal(obj) {
        var class_id = obj.options[obj.selectedIndex].value;
        if (!class_id) return;
        var url = "{{route('get.sections.courses.by.classId')}}?class_id=" + class_id
        fetch(url)
            .then((resp) => resp.json())
            .then(function (data) {
                var sectionSelect = document.getElementById('modal_section_id');
                sectionSelect.options.length = 0;
                sectionSelect.add(new Option('Select Section', ''));
                data.sections.forEach(function (section) {
                    sectionSelect.add(new Option(section.section_name, section.id));
                });
            })
            .catch(function (error) {
                console.log(error);
            });
    }

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
