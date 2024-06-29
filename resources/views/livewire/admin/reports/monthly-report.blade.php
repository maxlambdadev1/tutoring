<div>
    @php
    $title = "Monthly Report";
    $breadcrumbs = ["Reports", "Monthly"];
    @endphp
    <x-custom-header :title="$title" :breadcrumbs="$breadcrumbs" />


    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row" x-data="{isLoading : @entangle('isLoading') }">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="from_date" class="form-label">From</label>
                                <input type="text" class="form-control " name="from_date" id="from_date" wire:model="from_date" value="{{$from_date}}" x-ref="from_date">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="to_date" class="form-label">To</label>
                                <input type="text" class="form-control " name="to_date" id="to_date" wire:model="to_date" value="{{$to_date}}" x-ref="to_date">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="mb-3">
                                <label class="form-label">&nbsp;</label>
                                <button type="button" class="btn btn-outline-secondary waves-effect form-control" x-on:click="isLoading = true" x-show="isLoading == false" wire:click="viewReport($refs.from_date.value, $refs.to_date.value)">View report</button>
                                <div class="text-center" x-show="isLoading == true">
                                    <div class="spinner-border text-success" role="status"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <p class="fw-bold"> {{$from_date_last_year}}~{{$to_date_last_year}} <span class="text-danger">&nbsp; VS &nbsp;</span> {{$from_date}}~{{$to_date}}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card  mb-3">
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-4">
                            <p class="fw-bold">Total bookings</p>
                            <hr />
                            <div class="d-flex justify-content-around">
                                <p>{{$data['total_bookings']['past'] ?? '-'}}</p>
                                <p class="text-danger">{{$data['total_bookings']['current'] ?? '-'}}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <p class="fw-bold">Total conversion bookings</p>
                            <hr />
                            <div class="d-flex justify-content-around">
                                <p>{{$data['total_conversion_bookings']['past'] ?? '-'}}</p>
                                <p class="text-danger">{{$data['total_conversion_bookings']['current'] ?? '-'}}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <p class="fw-bold">Conversion bookings rate</p>
                            <hr />
                            <div class="d-flex justify-content-around">
                                <p>
                                    {{!empty($data['total_bookings']['past']) ? number_format(100 * $data['total_conversion_bookings']['past'] / $data['total_bookings']['past'], 1, '.', '') : '-'}} %
                                </p>
                                <p class="text-danger">
                                    {{!empty($data['total_bookings']['current']) ? number_format(100 * $data['total_conversion_bookings']['current'] / $data['total_bookings']['current'], 1, '.', '') : '-'}} %
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card  mb-3">
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-4">
                            <p class="fw-bold">First sessions</p>
                            <hr />
                            <div class="d-flex justify-content-around">
                                <p>{{$data['first_session_total']['past'] ?? '-'}}</p>
                                <p class="text-danger">{{$data['first_session_total']['current'] ?? '-'}}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <p class="fw-bold">First session confirmed</p>
                            <hr />
                            <div class="d-flex justify-content-around">
                                <p>{{$data['first_session_confirmed']['past'] ?? '-'}}</p>
                                <p class="text-danger">{{$data['first_session_confirmed']['current'] ?? '-'}}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <p class="fw-bold">First session canceled</p>
                            <hr />
                            <div class="d-flex justify-content-around">
                                <p>{{$data['first_session_cancelled']['past'] ?? '-'}}</p>
                                <p class="text-danger">{{$data['first_session_cancelled']['current'] ?? '-'}}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <p class="fw-bold">First session scheduled</p>
                            <hr />
                            <div class="d-flex justify-content-around">
                                <p>{{$data['first_session_scheduled']['past'] ?? '-'}}</p>
                                <p class="text-danger">{{$data['first_session_scheduled']['current'] ?? '-'}}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <p class="fw-bold">First session unconfirmed</p>
                            <hr />
                            <div class="d-flex justify-content-around">
                                <p>{{$data['first_session_unconfirmed']['past'] ?? '-'}}</p>
                                <p class="text-danger">{{$data['first_session_unconfirmed']['current'] ?? '-'}}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <p class="fw-bold">First session completed rate</p>
                            <hr />
                            <div class="d-flex justify-content-around">
                                <p>
                                    {{!empty($data['first_session_total']['past']) ? number_format(100 * $data['first_session_confirmed']['past'] / $data['first_session_total']['past'], 1, '.', '') : '-'}} %
                                </p>
                                <p class="text-danger">
                                    {{!empty($data['first_session_total']['current']) ? number_format(100 * (1 - $data['first_session_cancelled']['current'] / $data['first_session_total']['current']), 1, '.', '') : '-'}} %
                                </p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <p class="fw-bold">First session success</p>
                            <hr />
                            <div class="d-flex justify-content-around">
                                <p>{{$data['first_session_success']['past'] ?? '-'}}</p>
                                <p class="text-danger">{{$data['first_session_success']['current'] ?? '-'}}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <p class="fw-bold">First session success rate</p>
                            <hr />
                            <div class="d-flex justify-content-around">
                                <p>
                                    {{!empty($data['first_session_total']['past']) ? number_format(100 * $data['first_session_success']['past'] / $data['first_session_total']['past'], 1, '.', '') : '-'}} %
                                </p>
                                <p class="text-danger">
                                    {{!empty($data['first_session_total']['current']) ? number_format(100 * (1 - $data['first_session_success']['current'] / $data['first_session_total']['current']), 1, '.', '') : '-'}} %
                                </p>
                            </div>
                        </div>
                        <div class="col-md-4">
                        </div>
                    </div>
                    <div class="row mt-3 text-center">
                        <div class="col-md-8">
                            <p class="fw-bold">Total confirmed session</p>
                            <hr />
                            <div class="d-flex justify-content-around">
                                <p>
                                    {{$data['total_sessions']['past'] ?? '-'}}
                                    (F2F: {{$data['total_sessions_f2f']['past'] ?? '-'}},
                                    ONLINE: {{$data['total_sessions']['past'] - $data['total_sessions_f2f']['past']}})
                                </p>
                                <p class="text-danger">
                                    {{$data['total_sessions']['current'] ?? '-'}}
                                    (F2F: {{$data['total_sessions_f2f']['current'] ?? '-'}},
                                    ONLINE: {{$data['total_sessions']['current'] - $data['total_sessions_f2f']['current']}})
                                </p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <p class="fw-bold">Total tutoring hours</p>
                            <hr />
                            <div class="d-flex justify-content-around">
                                <p>{{$data['total_hours']['past'] ?? '-'}}</p>
                                <p class="text-danger">{{$data['total_hours']['current'] ?? '-'}}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card  mb-3">
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-4">
                            <p class="fw-bold">Total tutor applicants(Excluding out of area)</p>
                            <hr />
                            <div class="d-flex justify-content-around">
                                <p>{{$data['total_tutor_applicants']['past'] ?? '-'}}</p>
                                <p class="text-danger">{{$data['total_tutor_applicants']['current'] ?? '-'}}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <p class="fw-bold">New tutor registered</p>
                            <hr />
                            <div class="d-flex justify-content-around">
                                <p>{{$data['new_tutors_registered']['past'] ?? '-'}}</p>
                                <p class="text-danger">{{$data['new_tutors_registered']['current'] ?? '-'}}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <p class="fw-bold">Tuturs made inactive</p>
                            <hr />
                            <div class="d-flex justify-content-around">
                                <p>{{$data['tutors_inactive']['past'] ?? '-'}}</p>
                                <p class="text-danger">{{$data['tutors_inactive']['current'] ?? '-'}}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card  mb-3">
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-4"></div>
                        <div class="col-md-4">
                            <p class="fw-bold">Revenue percentage</p>
                            <hr />
                            <div class="d-flex justify-content-around">
                                <p>-</p>
                                <p class="text-danger">{{$data['revenue_percentage'] > 0 ? '+' .$data['revenue_percentage'] : ($data['revenue_percentage'] < 0 ? '-' .$data['revenue_percentage'] : '-') }}  %</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $('#from_date, #to_date').datepicker({
        autoclose: true,
        format: 'dd/mm/yyyy',
        todayHighlight: true,
    });
</script>