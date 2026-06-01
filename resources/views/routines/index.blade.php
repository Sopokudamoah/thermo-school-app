@extends('layouts.app')
@section('page-title', 'Timetable')

@section('content')

{{-- Page header --}}
<div class="mb-6 flex items-start justify-between gap-4">
    <div>
        <h1 class="font-heading text-xl font-bold text-gray-900">Timetable</h1>
        <nav class="flex items-center gap-1.5 mt-1 text-sm text-gray-500">
            <a href="{{ route('home') }}" class="hover:text-indigo-600 transition-colors">Home</a>
            <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
            <span class="text-gray-900">Timetable</span>
        </nav>
    </div>
    @can('create routines')
    <a href="{{ route('section.routine.create') }}"
       class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm px-4 py-2.5 rounded-lg transition-colors shrink-0">
        <i data-lucide="plus" class="w-4 h-4"></i>
        Add to Timetable
    </a>
    @endcan
</div>

@include('session-messages')

{{-- Filter card --}}
<div class="bg-white rounded-card shadow-card border border-gray-200 p-5 mb-5">
    <h2 class="font-heading text-sm font-semibold text-gray-700 uppercase tracking-wider mb-4">Select Class & Section</h2>
    <form method="GET" action="{{ route('routine.index') }}" class="flex flex-wrap items-end gap-3">
        {{-- Class --}}
        <div class="flex-1 min-w-[180px]">
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Class</label>
            <select id="class-select" name="class_id" onchange="fetchSections(this)"
                    class="block w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-colors" required>
                <option value="">— Select class —</option>
                @foreach($classes as $class)
                    <option value="{{ $class->id }}" {{ $selected_class_id == $class->id ? 'selected' : '' }}>
                        {{ $class->class_name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Section --}}
        <div class="flex-1 min-w-[180px]">
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Section</label>
            <select id="section-select" name="section_id"
                    class="block w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-colors" required>
                <option value="">— Select section —</option>
            </select>
        </div>

        <button type="submit"
                class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm px-5 py-2.5 rounded-lg transition-colors">
            <i data-lucide="eye" class="w-4 h-4"></i>
            View Timetable
        </button>
    </form>
</div>

{{-- Timetable --}}
@if($selected_class_id && $selected_section_id)
@php
    $dayNames = [
        1 => 'Monday', 2 => 'Tuesday', 3 => 'Wednesday',
        4 => 'Thursday', 5 => 'Friday', 6 => 'Saturday', 7 => 'Sunday',
    ];
    $dayAbbr = [
        1 => 'MON', 2 => 'TUE', 3 => 'WED',
        4 => 'THU', 5 => 'FRI', 6 => 'SAT', 7 => 'SUN',
    ];
    // Always show Mon-Fri; add Sat/Sun only if they have data
    $activeDays = [1, 2, 3, 4, 5];
    if ($routines->has(6)) $activeDays[] = 6;
    if ($routines->has(7)) $activeDays[] = 7;

    // Colour palette — picked by course_id mod count
    $palette = [
        ['bg' => '#E4F7FB', 'border' => '#00A8CC', 'text' => '#004E60', 'label' => '#00677E'],
        ['bg' => '#E8F5E9', 'border' => '#43A047', 'text' => '#1B5E20', 'label' => '#2E7D32'],
        ['bg' => '#FFF3E0', 'border' => '#FB8C00', 'text' => '#E65100', 'label' => '#F57C00'],
        ['bg' => '#F3E5F5', 'border' => '#8E24AA', 'text' => '#4A148C', 'label' => '#6A1B9A'],
        ['bg' => '#E8EAF6', 'border' => '#3949AB', 'text' => '#1A237E', 'label' => '#283593'],
        ['bg' => '#FCE4EC', 'border' => '#D81B60', 'text' => '#880E4F', 'label' => '#AD1457'],
        ['bg' => '#E0F7FA', 'border' => '#00ACC1', 'text' => '#006064', 'label' => '#00838F'],
        ['bg' => '#F1F8E9', 'border' => '#7CB342', 'text' => '#33691E', 'label' => '#558B2F'],
    ];

    // Build a stable course → colour map so the same course always gets the same colour
    $courseColours = [];
    $allRoutines = $routines->flatten();
    foreach ($allRoutines as $r) {
        $cid = $r->course_id ?? 0;
        if (!isset($courseColours[$cid])) {
            $courseColours[$cid] = $palette[count($courseColours) % count($palette)];
        }
    }

    $className   = $selected_class?->class_name   ?? '—';
    $sectionName = $selected_section?->section_name ?? '—';
@endphp

<div class="bg-white rounded-card shadow-card border border-gray-200 overflow-hidden">

    {{-- Timetable toolbar --}}
    <div class="flex items-center justify-between px-5 py-3.5 border-b border-gray-100">
        <div>
            <p class="font-heading font-bold text-gray-900 text-base leading-tight">{{ $className }} &mdash; {{ $sectionName }}</p>
            <p class="text-xs text-gray-500 mt-0.5">Weekly timetable</p>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="exportPNG()"
                    class="inline-flex items-center gap-1.5 text-xs font-medium px-3 py-2 rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 transition-colors">
                <i data-lucide="image-down" class="w-3.5 h-3.5"></i> PNG
            </button>
            <button onclick="exportPDF()"
                    class="inline-flex items-center gap-1.5 text-xs font-medium px-3 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white transition-colors">
                <i data-lucide="file-down" class="w-3.5 h-3.5"></i> Export PDF
            </button>
        </div>
    </div>

    {{-- Exportable region --}}
    <div id="timetable-export-area" style="background:#ffffff; padding: 24px;">

        {{-- Header shown inside the export --}}
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:20px; padding-bottom:16px; border-bottom:2px solid #E4F7FB;">
            <div>
                <p style="font-family:'Plus Jakarta Sans',sans-serif; font-size:18px; font-weight:700; color:#001B39; margin:0;">
                    {{ $className }} &mdash; {{ $sectionName }}
                </p>
                <p style="font-family:'Plus Jakarta Sans',sans-serif; font-size:12px; color:#6d797e; margin:4px 0 0;">Weekly Timetable</p>
            </div>
            <div style="background:#001B39; color:#fff; font-family:'Plus Jakarta Sans',sans-serif; font-size:11px; font-weight:600; padding:6px 14px; border-radius:20px; letter-spacing:0.04em;">
                {{ config('app.name') }}
            </div>
        </div>

        {{-- Day columns --}}
        <div style="display:grid; grid-template-columns:repeat({{ count($activeDays) }}, 1fr); gap:10px;">
            @foreach($activeDays as $day)
            @php $dayRoutines = $routines->get($day, collect()); @endphp
            <div>
                {{-- Day header --}}
                <div style="background:#001B39; color:#fff; text-align:center; padding:8px 4px; border-radius:8px 8px 0 0; font-family:'Plus Jakarta Sans',sans-serif; font-size:11px; font-weight:700; letter-spacing:0.06em;">
                    {{ $dayAbbr[$day] }}
                </div>

                {{-- Periods --}}
                <div style="background:#F7F9FB; border-radius:0 0 8px 8px; padding:6px; min-height:160px;">
                    @forelse($dayRoutines->sortBy('start') as $routine)
                    @php $colour = $courseColours[$routine->course_id ?? 0] ?? $palette[0]; @endphp
                    <div style="background:{{ $colour['bg'] }}; border-left:3px solid {{ $colour['border'] }}; border-radius:6px; padding:8px 10px; margin-bottom:6px;">
                        <p style="font-family:'Plus Jakarta Sans',sans-serif; font-size:12px; font-weight:700; color:{{ $colour['text'] }}; margin:0; line-height:1.3;">
                            {{ $routine->course->course_name }}
                        </p>
                        <p style="font-family:'Plus Jakarta Sans',sans-serif; font-size:10px; color:{{ $colour['label'] }}; margin:3px 0 0; opacity:0.8;">
                            {{ \Carbon\Carbon::parse($routine->start)->format('g:ia') }} &ndash; {{ \Carbon\Carbon::parse($routine->end)->format('g:ia') }}
                        </p>
                    </div>
                    @empty
                    <div style="display:flex; align-items:center; justify-content:center; height:80px;">
                        <p style="font-family:'Plus Jakarta Sans',sans-serif; font-size:11px; color:#bcc8ce;">No classes</p>
                    </div>
                    @endforelse
                </div>
            </div>
            @endforeach
        </div>

        {{-- Footer --}}
        <p style="font-family:'Plus Jakarta Sans',sans-serif; font-size:10px; color:#bcc8ce; text-align:right; margin-top:16px; padding-top:12px; border-top:1px solid #eceef0;">
            Generated {{ now()->format('d M Y') }}
        </p>
    </div>
</div>

@elseif(request()->has('class_id'))
{{-- Submitted but no results --}}
<div class="bg-white rounded-card shadow-card border border-gray-200 flex flex-col items-center py-14 text-center">
    <i data-lucide="clock-off" class="w-9 h-9 text-gray-300 mb-3"></i>
    <p class="font-heading font-semibold text-gray-700 mb-1">No timetable found</p>
    <p class="text-sm text-gray-500">No periods have been added for this class and section yet.</p>
    @can('create routines')
    <a href="{{ route('section.routine.create') }}"
       class="mt-4 inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm px-4 py-2 rounded-lg transition-colors">
        <i data-lucide="plus" class="w-4 h-4"></i> Add periods
    </a>
    @endcan
</div>
@endif

@endsection

@push('scripts')
<script>
    const SECTIONS_URL = "{{ route('get.sections.courses.by.classId') }}";
    const preselectedClassId   = {{ $selected_class_id ?? 0 }};
    const preselectedSectionId = {{ $selected_section_id ?? 0 }};

    function fetchSections(selectEl, preselectId = 0) {
        const classId = selectEl.value;
        const sectionSel = document.getElementById('section-select');
        sectionSel.innerHTML = '<option value="">Loading…</option>';

        if (!classId) {
            sectionSel.innerHTML = '<option value="">— Select section —</option>';
            return;
        }

        fetch(`${SECTIONS_URL}?class_id=${classId}`)
            .then(r => r.json())
            .then(data => {
                sectionSel.innerHTML = '<option value="">— Select section —</option>';
                data.sections.forEach(s => {
                    const opt = new Option(s.section_name, s.id);
                    if (s.id == preselectId) opt.selected = true;
                    sectionSel.add(opt);
                });
            })
            .catch(() => {
                sectionSel.innerHTML = '<option value="">— Select section —</option>';
            });
    }

    // Pre-populate sections when page reloads after form submission
    document.addEventListener('DOMContentLoaded', function () {
        if (preselectedClassId) {
            const classSelect = document.getElementById('class-select');
            fetchSections(classSelect, preselectedSectionId);
        }
    });

    // PDF export — landscape A4
    function exportPDF() {
        const btn = event.currentTarget;
        btn.textContent = 'Generating…';
        btn.disabled = true;

        const el = document.getElementById('timetable-export-area');
        html2canvas(el, { scale: 2, backgroundColor: '#ffffff', useCORS: true, logging: false })
            .then(canvas => {
                const { jsPDF } = window.jspdf;
                const pdf = new jsPDF('landscape', 'mm', 'a4');
                const pw  = pdf.internal.pageSize.getWidth();
                const ph  = (canvas.height * pw) / canvas.width;
                pdf.addImage(canvas.toDataURL('image/png'), 'PNG', 0, 0, pw, ph);
                pdf.save(`timetable-{{ $selected_class?->class_name ?? 'export' }}-{{ $selected_section?->section_name ?? '' }}.pdf`);
            })
            .finally(() => {
                btn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline;margin-right:4px;vertical-align:-2px"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="12" y1="18" x2="12" y2="12"></line><line x1="9" y1="15" x2="15" y2="15"></line></svg> Export PDF';
                btn.disabled = false;
            });
    }

    // PNG export — 2× scale for crisp output
    function exportPNG() {
        const btn = event.currentTarget;
        btn.textContent = 'Generating…';
        btn.disabled = true;

        const el = document.getElementById('timetable-export-area');
        html2canvas(el, { scale: 2, backgroundColor: '#ffffff', useCORS: true, logging: false })
            .then(canvas => {
                const link = document.createElement('a');
                link.download = `timetable-{{ $selected_class?->class_name ?? 'export' }}-{{ $selected_section?->section_name ?? '' }}.png`;
                link.href = canvas.toDataURL('image/png');
                link.click();
            })
            .finally(() => {
                btn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline;margin-right:4px;vertical-align:-2px"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg> PNG';
                btn.disabled = false;
            });
    }
</script>
@endpush
