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
            <i class="fa-duotone fa-home mr-1"></i>
            {{ __('Home') }}
        </h2>
    </x-slot>

    <div class="py-12" id="vue-app">
        <div class="container mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4">
                <div class="grid lg:grid-cols-6 gap-4">
                    <div class=" col-span-2">
                        <div class="card">
                            <h5 class="card-header">
                                <i class="fa-solid fa-user mr-1"></i>
                                User
                            </h5>
                            <div class="card-body">
                                <p class="text-lg font-bold">{{$user->name}}</p>
                                <p class="font-bold text-xs text-gray-500">{{$user->email}}</p>
                                <p class="font-bold text-xs text-gray-500">{{$user->phone_number}}</p>
                            </div>
                        </div>
                    </div>
                    <div class=" col-span-2 flex flex-col gap-4 items-center justify-center">
                        <div>
                            <span id="dateTime" class="text-2xl"></span>
                        </div>
                        <i class="fa-brands fa-slack display-1"></i>
                    </div>
                    <div class="col-span-2">
                        <div class="flex flex-col gap-4">
                            <div class="card">
                                <h5 class="card-header">
                                    <i class="fa-solid fa-money-bill mr-1"></i>
                                    Finance
                                </h5>
                                <div class="card-body">
                                    <img
                                        src="{{ asset('/img/coming-soon.jpg') }}"
                                        alt="Coming Soon"
                                         class="w-full rounded">
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="col-span-4">
                        <div class="card">
                            <h5 class="card-header">
                                <i class="fa-solid fa-calendar-alt mr-1"></i>
                                Lessons Schedule
                            </h5>
                            <div class="card-body">
                                <div id="teacher-calendar"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-2">
                        <div class="card">
                            <h5 class="card-header">
                                <i class="fa-solid fa-list  mr-1"></i>
                                Events list
                            </h5>
                            <div class="card-body">
                                <div class="list-group">
                                    <a href="#" class="list-group-item list-group-item-action " aria-current="true">
                                        The current link item
                                    </a>
                                    <a href="#" class="list-group-item list-group-item-action">A second link item</a>
                                    <a href="#" class="list-group-item list-group-item-action">A third link item</a>
                                    <a href="#" class="list-group-item list-group-item-action active">A fourth link item</a>
                                    <a href="#" class="list-group-item list-group-item-action">A third link item</a>
                                    <a href="#" class="list-group-item list-group-item-action">A fourth link item</a>
                                    <a href="#" class="list-group-item list-group-item-action">A third link item</a>
                                    <a href="#" class="list-group-item list-group-item-action">A fourth link item</a>
                                    <a href="#" class="list-group-item list-group-item-action">A third link item</a>
                                    <a href="#" class="list-group-item list-group-item-action">A fourth link item</a>
                                    <a href="#" class="list-group-item list-group-item-action">A third link item</a>
                                    <a href="#" class="list-group-item list-group-item-action">A fourth link item</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-full">
                        <div class="card">
                            <h5 class="card-header">
                                <i class="fa-solid fa-money-bill mr-1"></i>
                                Messages
                            </h5>
                            <div class="card-body">
                              <div class="flex max-h-[30rem] border border-gray-300 rounded-lg overflow-hidden">
                                  <div class="w-80 border-r border-gray-300 ">
                                      <div class="list-group list-group-flush">
                                          <a href="#" class="list-group-item list-group-item-action " aria-current="true">
                                              The current link item
                                          </a>
                                          <a href="#" class="list-group-item list-group-item-action">A second link item</a>
                                          <a href="#" class="list-group-item list-group-item-action">A third link item</a>
                                          <a href="#" class="list-group-item list-group-item-action active">A fourth link item</a>
                                      </div>
                                  </div>
                                  <div class="flex-1">

                                  </div>
                              </div>
                            </div>
                        </div>
                    </div>
                </div>
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
                        let start = moment($('#evtStart').val()).format('YYYY-MM-DDTHH:mm')
                        let end = moment($('#evtEnd').val()).format('YYYY-MM-DDTHH:mm')
                        let title = $('#eventTitle').val()
                        // this.calendar.render()
                        this.calendar.addEvent({
                            title: title, // a property!
                            start: start, // a property!
                            end: end // a property! ** see important note below about 'end' **
                        })
                        $('#teacher-calendar-modal').modal('hide');
                    },
                },
                mounted() {
                    const calendarEl = document.getElementById('teacher-calendar');
                    this.calendar = new FullCalendar.Calendar(calendarEl, {
                        initialView: 'dayGridMonth',
                        selectable: true,
                        select: (e) => {
                            $('#teacher-calendar-modal').find('#evtStart').val(moment(e.start).format('YYYY-MM-DD'));
                            $('#teacher-calendar-modal').find('#evtEnd').val(moment(e.end).format('YYYY-MM-DD'));
                            $('#teacher-calendar-modal').modal('show');
                        },
                        events: this.Events
                    });
                    this.calendar.render();
                    $('#submit_event').on('click', this.EventHandler)
                },
            });
            vueApp.mount('#vue-app');

        </script>
    @endpush
</x-app-layout>

