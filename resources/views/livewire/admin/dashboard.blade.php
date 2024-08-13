<div>
    @php
    $title = "Home";
    $breadcrumbs = ["Dashboard"];
    @endphp
    <x-custom-header :title="$title" :breadcrumbs="$breadcrumbs" />

    <div class="row" x-data="dashboard_init">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <div class="text-center">
                        <p class="mb-0 fw-bold">Total sessions last week: {{$total_sessions_week}}</p>
                        <p class="mb-0">Previous week: {{$total_sessions_previous}}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <div class="text-center">
                        <p class="mb-0 fw-bold">Scheduled sessions this week: {{$scheduled_sessions_week}}</p>
                        <p class="mb-0">Total scheduled sessions: {{$scheduled_sessions_total}}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <div class="text-center">
                        <p class="mb-0 fw-bold">First sessions this week: {{$first_sessions_week}}</p>
                        <p class="mb-0">Previous week: {{$first_sessions_previous}}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <div class="text-center text-white border py-2 {{ $daily_booking_target > $week_stats[0]['booking_target']['count'] ? 'bg-danger' : 'bg-success'}}">
                                <h4 class="my-0">BOOKINGS</h4>
                                <h4 class="">{{ $week_stats[0]['booking_target']['count']}}/{{$daily_booking_target}}</h4>
                                <div class="row">
                                    <div class="col-6">Booking form: {{ $week_stats[0]['booking_target']['booking_form']}}</div>
                                    <div class="col-6">Team: {{ $week_stats[0]['booking_target']['team']}}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <div class="text-center text-white border py-2 {{ $daily_conversion_target > $week_stats[0]['conversion_target']['count'] ? 'bg-danger' : 'bg-success'}}">
                                <h4 class="my-0">CONVERSIONS</h4>
                                <h4 class="">{{ $week_stats[0]['conversion_target']['count']}}/{{$daily_booking_target}}</h4>
                                <div class="row">
                                    <div class="col-6">Tutors: {{ $week_stats[0]['conversion_target']['tutor']}}</div>
                                    <div class="col-6">Team: {{ $week_stats[0]['conversion_target']['team']}}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <div class="text-center text-white border py-2 h-100 {{ $daily_first_session_target > $week_stats[0]['first_session_target']['count'] ? 'bg-danger' : 'bg-success'}}">
                                <h4 class="mt-0">1st SESSIONS</h4>
                                <h2 class="">{{ $week_stats[0]['first_session_target']['count']}}/{{$daily_booking_target}}</h2>
                            </div>
                        </div>
                        <div class="col-12 mb-2">
                            <div class="row mx-0 text-center">
                                <div class="col-md-1 fw-bold text-white border p-2 {{$daily_booking_target > $week_stats[0]['booking_target']['count'] ? 'bg-danger' : 'bg-success'}}">{{$week_stats[0]['date']}}</div>
                                <div class="col-md-1 fw-bold text-white border p-2 {{$daily_booking_target > $week_stats[1]['booking_target']['count'] ? 'bg-danger' : 'bg-success'}}">{{$week_stats[1]['date']}}</div>
                                <div class="col-md-1 fw-bold text-white border p-2 {{$daily_booking_target > $week_stats[2]['booking_target']['count'] ? 'bg-danger' : 'bg-success'}}">{{$week_stats[2]['date']}}</div>
                                <div class="col-md-1 fw-bold text-white border p-2 {{$daily_booking_target > $week_stats[3]['booking_target']['count'] ? 'bg-danger' : 'bg-success'}}">{{$week_stats[3]['date']}}</div>
                                <div class="col-md-1 fw-bold text-white border p-2 {{$daily_booking_target > $week_stats[4]['booking_target']['count'] ? 'bg-danger' : 'bg-success'}}">{{$week_stats[4]['date']}}</div>
                                <div class="col-md-1 fw-bold text-white border p-2 {{$daily_booking_target > $week_stats[5]['booking_target']['count'] ? 'bg-danger' : 'bg-success'}}">{{$week_stats[5]['date']}}</div>
                                <div class="col-md-1 fw-bold text-white border p-2 {{$daily_booking_target > $week_stats[6]['booking_target']['count'] ? 'bg-danger' : 'bg-success'}}">{{$week_stats[6]['date']}}</div>
                                <div class="col-md-5 border p-2 fw-bold">BOOKINGS</div>
                            </div>
                        </div>
                        <div class="col-12 mb-2">
                            <div class="row mx-0 text-center">
                                <div class="col-md-1 fw-bold text-white border p-2 {{$daily_conversion_target > $week_stats[0]['conversion_target']['count'] ? 'bg-danger' : 'bg-success'}}">{{$week_stats[0]['date']}}</div>
                                <div class="col-md-1 fw-bold text-white border p-2 {{$daily_conversion_target > $week_stats[1]['conversion_target']['count'] ? 'bg-danger' : 'bg-success'}}">{{$week_stats[1]['date']}}</div>
                                <div class="col-md-1 fw-bold text-white border p-2 {{$daily_conversion_target > $week_stats[2]['conversion_target']['count'] ? 'bg-danger' : 'bg-success'}}">{{$week_stats[2]['date']}}</div>
                                <div class="col-md-1 fw-bold text-white border p-2 {{$daily_conversion_target > $week_stats[3]['conversion_target']['count'] ? 'bg-danger' : 'bg-success'}}">{{$week_stats[3]['date']}}</div>
                                <div class="col-md-1 fw-bold text-white border p-2 {{$daily_conversion_target > $week_stats[4]['conversion_target']['count'] ? 'bg-danger' : 'bg-success'}}">{{$week_stats[4]['date']}}</div>
                                <div class="col-md-1 fw-bold text-white border p-2 {{$daily_conversion_target > $week_stats[5]['conversion_target']['count'] ? 'bg-danger' : 'bg-success'}}">{{$week_stats[5]['date']}}</div>
                                <div class="col-md-1 fw-bold text-white border p-2 {{$daily_conversion_target > $week_stats[6]['conversion_target']['count'] ? 'bg-danger' : 'bg-success'}}">{{$week_stats[6]['date']}}</div>
                                <div class="col-md-5 border p-2 fw-bold">CONVERSIONS</div>
                            </div>
                        </div>
                        <div class="col-12 mb-2">
                            <div class="row mx-0 text-center">
                                <div class="col-md-1 fw-bold text-white border p-2 {{$daily_first_session_target > $week_stats[0]['first_session_target']['count'] ? 'bg-danger' : 'bg-success'}}">{{$week_stats[0]['date']}}</div>
                                <div class="col-md-1 fw-bold text-white border p-2 {{$daily_first_session_target > $week_stats[1]['first_session_target']['count'] ? 'bg-danger' : 'bg-success'}}">{{$week_stats[1]['date']}}</div>
                                <div class="col-md-1 fw-bold text-white border p-2 {{$daily_first_session_target > $week_stats[2]['first_session_target']['count'] ? 'bg-danger' : 'bg-success'}}">{{$week_stats[2]['date']}}</div>
                                <div class="col-md-1 fw-bold text-white border p-2 {{$daily_first_session_target > $week_stats[3]['first_session_target']['count'] ? 'bg-danger' : 'bg-success'}}">{{$week_stats[3]['date']}}</div>
                                <div class="col-md-1 fw-bold text-white border p-2 {{$daily_first_session_target > $week_stats[4]['first_session_target']['count'] ? 'bg-danger' : 'bg-success'}}">{{$week_stats[4]['date']}}</div>
                                <div class="col-md-1 fw-bold text-white border p-2 {{$daily_first_session_target > $week_stats[5]['first_session_target']['count'] ? 'bg-danger' : 'bg-success'}}">{{$week_stats[5]['date']}}</div>
                                <div class="col-md-1 fw-bold text-white border p-2 {{$daily_first_session_target > $week_stats[6]['first_session_target']['count'] ? 'bg-danger' : 'bg-success'}}">{{$week_stats[6]['date']}}</div>
                                <div class="col-md-5 border p-2 fw-bold">1st SESSIONS</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('dashboard_init', () => ({}))
    })
</script>