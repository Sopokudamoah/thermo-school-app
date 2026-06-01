@extends('layouts.app')
@section('page-title', 'Routine')

@section('content')
    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="font-heading text-xl font-bold text-gray-900"><i data-lucide="clock"
                                                                        class="inline w-5 h-5 mr-2"></i> Routine</h1>
            <nav class="flex items-center gap-1.5 mt-1 text-sm text-gray-500">
                <a href="{{route('home')}}" class="hover:text-indigo-600">Home</a>
                <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                <a href="{{route('routine.index')}}" class="hover:text-indigo-600">Routine</a>
                <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                <span>View Routine</span>
            </nav>
        </div>
    </div>

    <div class="bg-white rounded-card shadow-card border border-gray-200 p-4 mb-6">
        <form action="{{ route('section.routine.show') }}" method="GET" class="flex flex-wrap items-center gap-4">
            <div class="flex items-center gap-2">
                <label class="whitespace-nowrap text-sm font-medium text-gray-700">Class:</label>
                <select name="class_id" required onchange="getSections(this)"
                        class="rounded-lg border border-gray-300 px-3 py-1.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 bg-white min-w-[150px]">
                    <option value="">Select Class</option>
                    @foreach($classes as $class)
                        <option
                            value="{{ $class->id }}" {{ $selected_class_id == $class->id ? 'selected' : '' }}>{{ $class->class_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-center gap-2">
                <label class="whitespace-nowrap text-sm font-medium text-gray-700">Section:</label>
                <select name="section_id" id="section_id" required
                        class="rounded-lg border border-gray-300 px-3 py-1.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 bg-white min-w-[150px]">
                    <option value="">Select Section</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit"
                        class="inline-flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-sm px-4 py-2 rounded-lg transition-colors">
                    <i data-lucide="search" class="w-4 h-4"></i> View Routine
                </button>
                @can('create routines')
                    <button type="button" @click="$dispatch('open-modal', 'add-routine')"
                            class="inline-flex items-center justify-center gap-2 bg-white hover:bg-gray-50 text-indigo-600 border border-indigo-200 font-semibold text-sm px-4 py-2 rounded-lg transition-colors">
                        <i data-lucide="plus" class="w-4 h-4"></i> Add Routine
                    </button>
                @endcan
            </div>
        </form>
    </div>

    @push('modals')
        <div x-show="activeModal === 'add-routine'"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-[70] overflow-y-auto"
             aria-labelledby="modal-title" role="dialog" aria-modal="true" x-cloak>
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" @click="closeModal()"
                     aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="activeModal === 'add-routine'"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                    <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-900">Add Routine Period</h3>
                        <button @click="closeModal()" class="text-gray-400 hover:text-gray-500">
                            <i data-lucide="x" class="w-5 h-5"></i>
                        </button>
                    </div>

                    <form action="{{ route('section.routine.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="session_id" value="{{ $current_school_session_id }}">

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Class <sup
                                        class="text-indigo-500">*</sup></label>
                                <select name="class_id" required onchange="getModalSectionsAndCourses(this)"
                                        class="block w-full rounded-lg border border-gray-300 px-3.5 py-2 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 bg-white">
                                    <option value="">Select Class</option>
                                    @foreach($classes as $class)
                                        <option
                                            value="{{ $class->id }}" {{ $selected_class_id == $class->id ? 'selected' : '' }}>{{ $class->class_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Section <sup
                                            class="text-indigo-500">*</sup></label>
                                    <select name="section_id" id="modal_section_id" required
                                            class="block w-full rounded-lg border border-gray-300 px-3.5 py-2 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 bg-white">
                                        <option value="">Select Section</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Course <sup
                                            class="text-indigo-500">*</sup></label>
                                    <select name="course_id" id="modal_course_id" required
                                            class="block w-full rounded-lg border border-gray-300 px-3.5 py-2 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 bg-white">
                                        <option value="">Select Course</option>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Week Day <sup
                                        class="text-indigo-500">*</sup></label>
                                <select name="weekday" required
                                        class="block w-full rounded-lg border border-gray-300 px-3.5 py-2 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 bg-white">
                                    <option value="1">Monday</option>
                                    <option value="2">Tuesday</option>
                                    <option value="3">Wednesday</option>
                                    <option value="4">Thursday</option>
                                    <option value="5">Friday</option>
                                    <option value="6">Saturday</option>
                                    <option value="7">Sunday</option>
                                </select>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Starts <sup
                                            class="text-indigo-500">*</sup></label>
                                    <input type="time" name="start" required
                                           class="block w-full rounded-lg border border-gray-300 px-3.5 py-2 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 bg-white">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Ends <sup
                                            class="text-indigo-500">*</sup></label>
                                    <input type="time" name="end" required
                                           class="block w-full rounded-lg border border-gray-300 px-3.5 py-2 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 bg-white">
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 flex items-center justify-end gap-3">
                            <button type="button" @click="closeModal()"
                                    class="px-4 py-2 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                Cancel
                            </button>
                            <button type="submit"
                                    class="px-4 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors shadow-sm">
                                Save Period
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endpush

@php
    function getDayName($weekday) {
        if($weekday == 1) {
            return "MONDAY";
        } else if($weekday == 2) {
            return "TUESDAY";
        } else if($weekday == 3) {
            return "WEDNESDAY";
        } else if($weekday == 4) {
            return "THURSDAY";
        } else if($weekday == 5) {
            return "FRIDAY";
        } else if($weekday == 6) {
            return "SATURDAY";
        } else if($weekday == 7) {
            return "SUNDAY";
        } else {
            return "Noday";
        }
    }
@endphp

@if(count($routines) > 0)
<div class="bg-white rounded-card shadow-card border border-gray-200 overflow-x-auto">
    <table class="w-full text-sm text-center">
        <tbody class="divide-y divide-gray-100">
            @foreach($routines as $day => $courses)
                <tr class="hover:bg-gray-50 transition-colors">
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider bg-gray-50 border-r border-gray-200 w-28">{{getDayName($day)}}</th>
                    @php
                        $courses = $courses->sortBy('start');
                    @endphp
                    @foreach($courses as $course)
                        <td class="px-4 py-3 text-gray-600 border-r border-gray-100 last:border-r-0">
                            <span class="block font-medium text-gray-800">{{$course->course->course_name}}</span>
                            <span class="text-xs text-gray-500">{{$course->start}} - {{$course->end}}</span>
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@else
<div class="flex flex-col items-center py-10 text-center">
    <i data-lucide="clock" class="w-8 h-8 text-gray-300 mb-2"></i>
    <p class="text-sm text-gray-500">No routine has been added yet.</p>
</div>
@endif

    @push('scripts')
        <script>
            function getSections(obj) {
                var class_id = obj.options[obj.selectedIndex].value;
                if (!class_id) return;
                var url = "{{route('get.sections.courses.by.classId')}}?class_id=" + class_id
                fetch(url)
                    .then((resp) => resp.json())
                    .then(function (data) {
                        var sectionSelect = document.getElementById('section_id');
                        sectionSelect.options.length = 0;
                        sectionSelect.add(new Option('Select Section', ''));
                        data.sections.forEach(function (section) {
                            var option = new Option(section.section_name, section.id);
                            if (section.id == "{{ $selected_section_id }}") {
                                option.selected = true;
                            }
                            sectionSelect.add(option);
                        });
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            }

            function getModalSectionsAndCourses(obj) {
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

                        var courseSelect = document.getElementById('modal_course_id');
                        courseSelect.options.length = 0;
                        courseSelect.add(new Option('Select Course', ''));
                        data.courses.forEach(function (course) {
                            courseSelect.add(new Option(course.course_name, course.id));
                        });
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            }

            // Trigger on page load if class is selected
            document.addEventListener('DOMContentLoaded', function () {
                var classSelect = document.querySelector('select[name="class_id"]');
                if (classSelect && classSelect.value) {
                    getSections(classSelect);
                }

                var modalClassSelect = document.querySelector('select[onchange="getModalSectionsAndCourses(this)"]');
                if (modalClassSelect && modalClassSelect.value) {
                    getModalSectionsAndCourses(modalClassSelect);
                }
            });
        </script>
    @endpush

@endsection
