<div>
    @php
    $title = "Jobs";
    $breadcrumbs = ["Jobs", "All jobs"];
    @endphp
    <x-custom-header :title="$title" :breadcrumbs="$breadcrumbs" />

    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="mb-3">
                        <h4>All Job Opportunities:</h4>
                        <p>Here you will find all students currently seeking a tutor.</p>
                        <p>You can toggle between <b>Instant Accept face-to-face</b>, <b>Instant Accept online</b> and <b>Waiting List</b> opportunities below.
                            Please ensure you read the details of each opportunity carefully and only accept students you are confident you can commit to long term; the long term relationship is where the magic happens!</p>
                        <p class="mb-0">For all <b>Instant Accept</b> jobs the parent has provided their available times and days, and if you can match these then you can accept the student instantly.
                            For <b>Waiting List</b> opportunities, you provide the days and times that you can do and we offer them to the parent, allowing 48 hours to respond.</p>
                        @if (!$tutor->have_wwcc)
                        <p class="my-1 text-warning">You do not have a valid Working With Children Check or application number on file, and can therefore not accept jobs. Update your WWCC details <a href="{{route('tutor.your-detail.update-detail')}}" wire:navigate>here</a>
                        </p>
                        @endif
                        @if (!$tutor->accept_job_status)
                        <p class="my-1 text-warning">Please get in touch via live chat if you wish to work with a student listed here.</p>
                        @endif
                    </div>
                    <ul class="nav nav-tabs mb-3 d-block d-md-flex">
                        <li class="nav-item">
                            <a href="#instance-f2f" data-bs-toggle="tab" aria-expanded="false" class="nav-link {{$type == 'instance-f2f' ? 'active bg-light' : ''}}" wire:click="changeType('instance-f2f')">
                                <span class="d-md-block">Instance Accept Face-To-Face</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#instance-online" data-bs-toggle="tab" aria-expanded="true" class="nav-link {{$type == 'instance-online' ? 'active bg-light' : ''}}" wire:click="changeType('instance-online')">
                                <span class="d-md-block">Instance Accept Online</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#waiting-list" data-bs-toggle="tab" aria-expanded="false" class="nav-link {{$type == 'waiting-list' ? 'active bg-light' : ''}}" wire:click="changeType('waiting-list')">
                                <span class="d-md-block">Waiting List</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            @if (count($jobs) > 0)
            @if (!!$under_18 && $type=='instance-f2f')
            <div class="text-center my-5">
                <span class="alert alert-danger" role="alert">
                    <strong>You will be able to work with face-to-face students once you turn 18 and submit your Working with Children Check. Until then please check out our online student opportunities to find a student that is right for you!</strong>
                </span>
            </div>
            @else
            <p>Showing {{count($jobs)}} jobs</p>
            @foreach ($jobs as $job)
            @php $btn_disabled = false; 
                if (!$tutor->have_wwcc || !$tutor->accept_job_status) $btn_disabled = true; 
            @endphp
            @if (!!$under_18 && $job->session_type_id == 1)
            @else
            <div class="card mb-2 ribbon-box">
                <div class="card-body py-2">
                    @if ($job->job_type == 'replacement')
                    <div class="ribbon-two ribbon-two-info"><span>Replacement</span></div>
                    @elseif ($job->job_type == 'hot')
                    @if ($job->session_type_id == 1 && !empty($job->job_offer_price))
                    <div class="ribbon-two ribbon-two-danger"><span>${{$job->job_offer_price}}p/h</span></div>
                    @else
                    <div class="ribbon-two ribbon-two-danger"><span>HOT</span></div>
                    @endif
                    @endif
                    <div class="d-flex align-items-center">
                        <div class="text-center flex-grow-1 pe-2">
                            <p class="fw-bold mb-1">{{$job->session_type_id == 1 ? 'FACE TO FACE' : 'ONLINE'}}</p>
                            <div class="fw-bold">
                                Year {{$job->child->child_year ?? ''}} // {{$job->subject}} // {{$job->parent->parent_state ?? ''}} // {{$job->location}}
                            </div>
                            @if ($job->session_type_id == 1)
                            <div class="text-muted"><i class="uil uil-location-point"></i>{{round($job->distance, 2)}}km away from you</div>
                            @endif
                            @if (!empty($job->prefered_gender) && $tutor->gender != $job->prefered_gender)
                            @php $btn_disabled = true; @endphp
                            <p class="my-1 text-danger">{{$job->prefered_gender}} tutor only</p>
                            @endif
                            @if (!!$job->experienced_tutor && !$tutor->experienced_tutor)
                            @php $btn_disabled = true; @endphp
                            <p class="my-1 text-danger">This job is for only experienced tutors.</p>
                            @endif
                        </div>
                        @if ($btn_disabled)
                        <button type="button" disabled class="btn btn-outline-success">
                            Detail</button>
                        @else
                        <a type="button" href="/jobs/{{$job->id}}" wire:navigate class="btn btn-outline-success">
                            Detail</a>
                        @endif
                    </div>
                </div>
            </div>
            @endif
            @endforeach
            @endif
            @else
            <p>There are no jobs</p>
            @endif
        </div>
    </div>
</div>