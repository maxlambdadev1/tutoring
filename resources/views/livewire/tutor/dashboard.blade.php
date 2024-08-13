<div>
    @php
    $title = "Home";
    $breadcrumbs = ["Dashboard"];
    @endphp
    <x-custom-header :title="$title" :breadcrumbs="$breadcrumbs" />

    <div class="row" x-data="dashboard_init">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-4">
                            <p class="fw-bold mb-2">{{$total_sessions}}</p>
                            <div>TOTAL SESSIONS</div>
                        </div>
                        <div class="col-4">
                            <p class="fw-bold mb-2">{{$last_week_sessions}}</p>
                            <div>LAST WEEK</div>
                        </div>
                        <div class="col-4">
                            <p class="fw-bold mb-2">{{$this_week_sessions}}</p>
                            <div>THIS WEEK</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if (!empty($announcement))
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body bg-warning text-white">
                    <h4 class="fw-bold mb-2">Announcements:</h4>
                    <p class="mb-0">{!!$announcement->an_text !!}</p>
                </div>
            </div>
        </div>
        @endif
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="my-0">Tasks</h4>
                    <hr />
                    <div class="overflow-auto" style="max-height: 300px;">
                        @forelse ($tasks as $task)
                        <div class="card mb-2 border-bottom shadow-none p-1">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <span class="text-muted">{{$task->task_date}} // </span>
                                    <a href="{{$task->task_content}}">{{$task->task_name}}</a>
                                </div>
                                <div class="">
                                    <a href="javascript:void(0);" class="btn btn-link text-danger btn-lg p-0" x-on:click="taskHide({{$task->task_id}})">
                                        <i class="uil uil-multiply"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @empty
                        @foreach ($random_tasks as $task)
                        <div class="card mb-2 border-bottom shadow-none p-1">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <span class="text-muted">{{date('d/m/Y')}} // </span>
                                    <a href="{{$task['task_content']}}" target="_blank">{{$task['task_name']}}</a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body bg-danger text-white">
                    <h4 class="my-0">Newest jobs</h4>
                    <hr />
                    @forelse ($new_jobs as $job)
                    <div class="card bg-danger mb-2 border-bottom shadow-none p-1 text-white">
                        <div class="mb-1">
                            <span>{{$job->child->child_year ?? ''}} // {{$job->subject ?? ''}} // {{ $job->session_type_id == 1 ? $job->location : 'Online student'}} </span>
                            <a href="{{route('tutor.jobs.all-jobs')}}">Learn more</a>
                        </div>
                        <div>{{round((time() - $job->last_updated_timestamp) / 3600, 0)}} hours ago</div>
                    </div>
                    @empty
                    @endforelse
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="my-0">Upcoming sessions</h4>
                    <hr />
                    <div id='calendar' classs="w-100"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var schedule_sessions_event_init = function() {
        let events = @json($scheduled_sessions_events);

        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
            timeZone: 'UTC',
            initialView: 'dayGridWeek',
            events: events,
            editable: true,
            selectable: true,
            height: '250px'
        });

        calendar.render();
    };

    var dashboard_init = function () {
        Alpine.data('dashboard_init', () => ({
            init() {
                schedule_sessions_event_init();
            },
            taskHide(task_id) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Remove Task',
                    text: 'This will remove this task from your Dashboard.',
                    showCancelButton: true,
                    confirmButtonColor: '#DD6B55',
                    confirmButtonText: 'Confirm'
                }).then(async (result) => {
                    if (result.isConfirmed) {
                        await @this.call('taskHide', task_id);
                        schedule_sessions_event_init();
                    }
                });
            }
        }))
    }
    
    if (typeof Alpine == 'undefined') {
        document.addEventListener('alpine:init', () => { dashboard_init(); });
    } else {
        dashboard_init();
    }
</script>