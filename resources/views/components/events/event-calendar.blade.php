<div id='full_calendar_events' class="w-full"></div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var SITEURL = "{{ url('/') }}";
        var {Calendar, dayGridPlugin, timeGridPlugin, listPlugin, interactionPlugin} = window.FullCalendar;

        var calendarEl = document.getElementById('full_calendar_events');

        var calendar = new Calendar(calendarEl, {
            plugins: [dayGridPlugin, timeGridPlugin, listPlugin, interactionPlugin],
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,listWeek'
            },
            height: {!! ($editable == 'true') ? 500 : "'auto'" !!},
            editable: {{ $editable }},
            selectable: {{ $selectable }},
            eventLimit: true,
            events: SITEURL + '/calendar-event',
            displayEventTime: true,

            select: function (info) {
                @if($selectable == 'true')
                var event_name = prompt("Event Name:");
                if (event_name) {
                    fetch(SITEURL + '/calendar-crud-ajax', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            title: event_name,
                            start: info.startStr,
                            end: info.endStr,
                            type: 'create'
                        })
                    })
                        .then(res => res.json())
                        .then(data => {
                            calendar.addEvent({id: data.id, title: event_name, start: info.startStr, end: info.endStr});
                            if (window.toastr) toastr.success('Event created.', 'Event');
                        });
                }
                calendar.unselect();
                @endif
            },

            eventResize: function (info) {
                fetch(SITEURL + '/calendar-crud-ajax', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        title: info.event.title,
                        start: info.event.startStr,
                        end: info.event.endStr,
                        id: info.event.id,
                        type: 'edit'
                    })
                })
                    .then(() => {
                        if (window.toastr) toastr.success('Event updated.', 'Event');
                    });
            },

            eventClick: function (info) {
                @if($selectable == 'true')
                if (confirm("Are you sure you want to delete this event?")) {
                    fetch(SITEURL + '/calendar-crud-ajax', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({id: info.event.id, type: 'delete'})
                    })
                        .then(() => {
                            info.event.remove();
                            if (window.toastr) toastr.success('Event removed.', 'Event');
                        });
                }
                @endif
            }
    });

        calendar.render();
    });
</script>
@endpush
