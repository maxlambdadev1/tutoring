<div>
    @php
    $title = "Job detail";
    $breadcrumbs = ["Jobs", "Job detail"];
    @endphp
    <x-custom-header :title="$title" :breadcrumbs="$breadcrumbs" />

    <div class="row" x-data="job_detail_init">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-body">
                    @if (!empty($job))
                    <h3 class="mb-3 mb-md-4 text-warning">Is this your newest student?</h3>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-4 mb-2 fw-bold">Session Type</div>
                                <div class="col-8 mb-2">{{$job->session_type_id == 1 ? 'FACE TO FACE' : 'ONLINE'}}</div>
                            </div>
                            <div class="row">
                                <div class="col-4 mb-2 fw-bold">State</div>
                                <div class="col-8 mb-2">{{$job->parent->parent_state ?? ''}}</div>
                            </div>
                            <div class="row">
                                <div class="col-4 mb-2 fw-bold">Subject</div>
                                <div class="col-8 mb-2">{{$job->subject ?? ''}}</div>
                            </div>
                            <div class="row">
                                <div class="col-4 mb-2 fw-bold">Grade</div>
                                <div class="col-8 mb-2">Year {{$job->child->child_year ?? ''}}</div>
                            </div>
                            <div class="row">
                                <div class="col-4 mb-2 fw-bold">Suburb</div>
                                <div class="col-8 mb-2">{{$job->parent->parent_suburb ?? ''}}</div>
                            </div>
                            <div class="row">
                                <div class="col-4 mb-2 fw-bold">Availability</div>
                                <div class="col-8 mb-2">
                                    @foreach ($job->availabilities1 as $av)
                                    <div>{{$av}}</div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-2">
                            @if ($job->session_type_id == 1)
                            <div id="map" class="h-100" style="min-height:200px;">
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2 mb-2 fw-bold">Notes</div>
                        <div class="col-md-10 mb-2">{!! $job->job_notes ?? '-' !!}</div>
                    </div>
                    @if (!empty($job->main_result))
                    <div class="row">
                        <div class="col-md-5 mb-2 fw-bold">What is the main result you are looking for?</div>
                        <div class="col-md-7 mb-2">
                            <span class="badge badge-{{$job->main_result == 'Get better results at school' ? 'success' : 'secondary'}}-lighten fs-5 mb-1 fw-normal">Get better results at school</span>
                            <span class="badge badge-{{$job->main_result == 'Boost confidence' ? 'success' : 'secondary'}}-lighten fs-5 mb-1 fw-normal">Boost confidence</span>
                            <span class="badge badge-{{$job->main_result == 'Enjoy the learning experience' ? 'success' : 'secondary'}}-lighten fs-5 mb-1 fw-normal">Enjoy the learning experience</span>
                            <span class="badge badge-{{$job->main_result == 'A mix of all' ? 'success' : 'secondary'}}-lighten fs-5 mb-1 fw-normal">A mix of all</span>
                        </div>
                    </div>
                    @endif
                    @if (!empty($job->performance))
                    <div class="row">
                        <div class="col-md-5 mb-2 fw-bold">How would you describe their current performance at school?</div>
                        <div class="col-md-7 mb-2">
                            <span class="badge badge-{{$job->performance == 'Struggling at school' ? 'success' : 'secondary'}}-lighten fs-5 mb-1 fw-normal">Struggling at school</span>
                            <span class="badge badge-{{$job->performance == 'Excelling at school' ? 'success' : 'secondary'}}-lighten fs-5 mb-1 fw-normal">Excelling at school</span>
                            <span class="badge badge-{{$job->performance == 'Somewhere in between' ? 'success' : 'secondary'}}-lighten fs-5 mb-1 fw-normal">Somewhere in between</span>
                        </div>
                    </div>
                    @endif
                    @if (!empty($job->attitude))
                    <div class="row">
                        <div class="col-md-5 mb-2 fw-bold">How would you describe their attitude towards school?</div>
                        <div class="col-md-7 mb-2">
                            <span class="badge badge-{{$job->attitude == 'They love school' ? 'success' : 'secondary'}}-lighten fs-5 mb-1 fw-normal">They love school</span>
                            <span class="badge badge-{{$job->attitude == 'They dislike school' ? 'success' : 'secondary'}}-lighten fs-5 mb-1 fw-normal">They dislike school</span>
                            <span class="badge badge-{{$job->attitude == 'Somewhere in between' ? 'success' : 'secondary'}}-lighten fs-5 mb-1 fw-normal">Somewhere in between</span>
                        </div>
                    </div>
                    @endif
                    @if (!empty($job->mind))
                    <div class="row">
                        <div class="col-md-5 mb-2 fw-bold">How would you describe their mind?</div>
                        <div class="col-md-7 mb-2">
                            <span class="badge badge-{{$job->mind == 'Creative' ? 'success' : 'secondary'}}-lighten fs-5 mb-1 fw-normal">Creative</span>
                            <span class="badge badge-{{$job->mind == 'Analytical' ? 'success' : 'secondary'}}-lighten fs-5 mb-1 fw-normal">Analytical</span>
                            <span class="badge badge-{{$job->mind == 'Somewhere in between' ? 'success' : 'secondary'}}-lighten fs-5 mb-1 fw-normal">Somewhere in between</span>
                        </div>
                    </div>
                    @endif
                    @if (!empty($job->personality))
                    <div class="row">
                        <div class="col-md-5 mb-2 fw-bold">How would you describe their personality?</div>
                        <div class="col-md-7 mb-2">
                            <span class="badge badge-{{$job->personality == 'Shy' ? 'success' : 'secondary'}}-lighten fs-5 mb-1 fw-normal">Shy</span>
                            <span class="badge badge-{{$job->personality == 'Outgoing' ? 'success' : 'secondary'}}-lighten fs-5 mb-1 fw-normal">Outgoing</span>
                            <span class="badge badge-{{$job->personality == 'Somewhere in between' ? 'success' : 'secondary'}}-lighten fs-5 mb-1 fw-normal">Somewhere in between</span>
                        </div>
                    </div>
                    @endif
                    @if ($job->job_status == 3)
                    <h4 class="text-danger mb-2">This is an opportunity from our Waiting List</h4>
                    <p class="mb-2">This means that instead of accepting it like a normal student opportunity, you apply to work with them and we will send your details to the parent to accept. You will specify all the days and times you can work with the student and the parent will choose whether or not they would like to proceed with the first lesson.</p>
                    <p class="mb-2">Once you apply, the job opportunity will be hidden so that no one else can apply. The parent is given 48 hours to accept or decline; if they decline or are unresponsive we will let you know by email. If they accept you will receive first lesson details by SMS and email like a normal new student. You can only apply to work with each student once.
                    </p>
                    <div class="form-check mb-2">
                        <input type="checkbox" id="accept_job_condition" name="accept_job_condition" class="form-check-input" x-model="accept_job_condition">
                        <label for="accept_job_condition" class="form-check-label">I have read the student details and understand that the first lesson proceeding will be decided by the parent. I will receive an update within 48 hours.</label>
                    </div>
                    <div class="text-center mb-2">
                        <button type="button" class="btn btn-success" :disabled="!accept_job_condition"
                        data-bs-toggle="modal" data-bs-target="#rescheduleJobApplyModal{{$job->id}}">Apply</button>
                    </div>
                    @else
                    <div class="row align-items-center">
                        @if ($job->job_type == 'hot')
                        <div class="col-md-6 text-center mb-2">
                            <a href="{{env('HOT_JOB_TUTORHUB_ENDPOINT')}}" target="_blank" class="position-relative">
                                <img src="/images/hotlead.jpg" width="150" />
                                @if (!empty($job->job_offer) && !empty($job->job_offer->offer_amount))
                                <span class="position-absolute fw-bold text-white" style="right: 15%; top: 100%;">+${{$job->job_offer->offer_amount}}/H</span>
                                @endif
                            </a>
                        </div>
                        @endif
                        <div class="col-md-1"></div>
                        <div class="col-md-3 text-center border border-warning border-2 pt-2 mb-2">
                            <div class="mb-2">
                                <label for="job_availability" class="form-label">Please select a session date</label>
                                <select name="job_availability" id="job_availability" class="form-select" wire:model="job_availability" x-model="job_availability">
                                    <option value=""></option>
                                    @forelse ($job->formatted_date as $av => $item)
                                    <option value="{{$av}}">{{$item['date']}}</option>
                                    @empty
                                    @endforelse
                                </select>
                            </div>
                            <div class="mb-2">
                                @forelse ($job->formatted_date as $av => $item)
                                <div class="fw-bold" x-show="job_availability == '{{$av}}'">Your first session will be on {{$item['full_date']}}</div>
                                @empty
                                @endforelse
                            </div>
                        </div>
                    </div>
                    <h4 class="text-danger mb-2">Before you accept</h4>
                    <p class="mb-2">Please ensure you are <b>100% </b>certain you can commit to the first session - accepting a session and then changing your mind will result in fewer students offers in the future.</p>
                    <input type="hidden" x-ref="special_request_content" value="{{$job->special_request_content}}" />
                    @if (!empty($job->special_request_content))
                    <div class="mb-2 bg-warning p-2">
                        <p class="fw-bold mb-1">This booking has a special requirement from the parent</p>
                        <p class="">{{$job->special_request_content}}</p>
                        <hr />
                        <label for="achivement" class="form-label">To ensure you are the right tutor for this student, please explain how you meet the requirements listed above</label>
                        <textarea class="form-control" wire:model="special_request_response" id="special_request_response" rows="3" x-model="special_request_response"></textarea>
                    </div>
                    @endif
                    <div class="form-check mb-2">
                        <input type="checkbox" id="accept_job_condition" name="accept_job_condition" class="form-check-input" x-model="accept_job_condition">
                        <label for="accept_job_condition" class="form-check-label">I have read and accept all requirements for this job and can commit to the first session as detailed above.</label>
                    </div>
                    <div class="text-center mb-2">
                        <button type="button" class="btn btn-primary" :disabled="!accept_job_condition"
                        x-on:click="acceptJob" >Accept</button>
                    </div>
                    @if ((time() - \DateTime::createFromFormat('d/m/Y H:i', $job->create_time)->getTimestamp())/86400 >= 2)
                    <div class="mb-2 text-decoration-underline text-center cursor-pointer" data-bs-toggle="modal" data-bs-target="#rescheduleFirstSessionModal{{$job->id}}">
                        Propose a different first session date and time
                    </div>
                    @endif
                    @endif
                    @else
                    <div class="text-center my-5">
                        <span class="alert alert-danger" role="alert">
                            <strong>The job does not exist.</strong>
                        </span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <livewire:tutor.components.reschedule-first-session-modal job_id="{{$job->id}}" />
    <livewire:tutor.components.reschedule-job-apply-modal job_id="{{$job->id}}" />
</div>

<script>
    var job_detail_init = () => {
        Alpine.data('job_detail_init', () => ({
            accept_job_condition: false,
            job_availability: '',
            special_request_response: '',
            init() {
                this.initMap();
            },
            async initMap() { 
                let session_type_id = @json($job->session_type_id);
                if (session_type_id != 1) return;   

                console.log('initMap');
                var coords = @json($job->coords);
                var latLng = new google.maps.LatLng(-33.788837, 151.2841562);
                var directionsDisplay = new google.maps.DirectionsRenderer();
                var directionsService = new google.maps.DirectionsService();

                var mapOptions = {
                    center: latLng,
                    zoom: 15,
                    //mapTypeId: google.maps.MapTypeId.ROADMAP
                };

                var map = new google.maps.Map(document.getElementById('map'), mapOptions);
                directionsDisplay.setMap(map);

                var start = new google.maps.LatLng(coords.tutor_lat, coords.tutor_lon);
                var end = new google.maps.LatLng(coords.child_lat, coords.child_lon);

                this.setRoute(directionsService, directionsDisplay, start, end, map);
            },
            acceptJob() {
                try {                            
                    console.log('aaa');
                    if (this.job_availability == '') throw new Error('Please select session date');
                    if (this.$refs.special_request_content.value != '' && this.special_request_response == '') throw new Error('Please input the response');
                    
                    if (this.special_request_response.length > 0) {
                        Swal.fire({
                            title: "Are you sure?",
                            html: `<div>
                                        <h5 class='mt-3'><b>The parent's requirement from a tutor:</b></h5>
                                        <p>{{$job->special_request_content}}</p>
                                        <h5 class='mt-3'><b>How you meet the requirement:</b></h5>
                                        <p >${this.special_request_response}</p>
                                    </div>`,
                            icon: "info",
                            showCancelButton: true,
                            confirmButtonColor: "#DD6B55",
                            confirmButtonText: "Yes"
                        }).then(async (result) => {
                            if (result.isConfirmed) {
                                this.submit(); 
                            } else return;
                        });
                    } else {
                        this.submit();
                    }
                } catch (error) {
                    toastr.error(error.message);
                }
            },
            async submit() {
                let result = await @this.call('acceptJob');
                let temp = this;
                setTimeout(() => this.initMap(), 100); 
                if (!!result) {
                    let title = 'This student is yours!';
                    let text = 'Please check your email for details!';
                    if (this.special_request_response.length > 0) {
                        title = 'Your application has been received';
                        text = 'As this student booking includes a special requirement we need to review your profile to ensure you meet the requirements of the parent before we can confirm it. We will get back to you ASAP!';
                    }
                    Swal.fire({
                        title: title,
                        text : text,
                        icon: "success",
                        confirmButtonText: "Ok"
                    }).then(() => {
                    });
                }
            },
            //Maps functions
            setRoute(directionsService, directionsDisplay, start, end, map) {
                var request = {
                    origin: start,
                    destination: end,
                    travelMode: google.maps.TravelMode.DRIVING
                };

                directionsService.route(request, function (response, status) {
                    if (status == google.maps.DirectionsStatus.OK) {
                        directionsDisplay.setDirections(response);
                        directionsDisplay.setMap(map);
                    }
                });
            }
        }))
    };

    if (typeof Alpine == 'undefined') {
        document.addEventListener('alpine:init', () => { job_detail_init(); });
    } else {
        job_detail_init();
    }
</script>