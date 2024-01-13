@push('css')

    <link
        href="https://cdn.datatables.net/v/bs5/dt-1.13.8/date-1.5.1/fc-4.3.0/fh-3.4.0/sc-2.3.0/sl-1.7.0/datatables.min.css"
        rel="stylesheet">

    @vite('resources/css/admin.index.css')
@endpush

@push('js')
    <script src='//cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.js'></script>
    <script
        src="https://cdn.datatables.net/v/bs5/dt-1.13.8/date-1.5.1/fc-4.3.0/fh-3.4.0/sc-2.3.0/sl-1.7.0/datatables.min.js"></script>

@endpush


<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <i class="fa-duotone fa-calendar-alt mr-1"></i>
            {{ __('Scheduler') }}
        </h2>
    </x-slot>

    <div class="py-12" id="vue-app">
        <div class="container mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4">
                <div id="scheduler-calendar">   </div>
            </div>
        </div>
    </div>
    @push('js')
        <script>
            let dateTime = setInterval(() => {
                $('#dateTime').html(moment().format('LLL'))
            }, 1000)


            const vueApp = Vue.createApp({
                data() {
                    // init Calendar
                    let Events = [
                        { // this object will be "parsed" into an Event Object
                            title: 'The Title', // a property!
                            start: '2023-12-20', // a property!
                            end: '2023-12-22' // a property! ** see important note below about 'end' **
                        }
                    ]
                    return {
                        Events,
                        calendar: '',
                    }
                },
                methods: {
                    EventHandler() {

                    },
                },
                mounted() {
                    const calendarEl = document.getElementById('scheduler-calendar');
                    this.calendar = new FullCalendar.Calendar(calendarEl, {
                        initialView: 'timeGridWeek',
                        selectable: true,
                        select: (e) => {
                            $('#teacher-calendar-modal').find('#evtStart').val(moment(e.start).format('YYYY-MM-DD'));
                            $('#teacher-calendar-modal').find('#evtEnd').val(moment(e.end).format('YYYY-MM-DD'));
                            $('#teacher-calendar-modal').modal('show');
                        },
                        // events: this.Events
                    });
                    this.calendar.render();
                    $('#submit_event').on('click', this.EventHandler)
                },
            });
            vueApp.mount('#vue-app');

        </script>
    @endpush
</x-app-layout>

