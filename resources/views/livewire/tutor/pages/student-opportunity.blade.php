<div>
    @section('title')
    Student opportunity
    @endsection
    @section('description')
    @endsection
    @section('script')
    <script src="https://maps.googleapis.com/maps/api/js?libraries=maps,places,marker&key=AIzaSyAOIopVJmkbjQFH8B9Sy3RpZLJzUQGjHnY&loading=async&callback=student_opportunity" async defer></script>
    @endsection

    <div class="text-center" style="height:100vh;" x-data="student_opportunity_init">
        <div class="container">
            <div class="row">
                <div class="col-12 mt-3">
                    <h2 class="mb-3 mb-md-4">NEW STUDENT OPPORTUNITY</h2>
                    <p class="fw-bold mb-1">GRADE</p>
                    <p class="mb-3 mb-md-4">{{$job->child->child_year ?? '-'}}</p>
                    <p class="fw-bold mb-1">SUBJECT</p>
                    @if ($job->job_type == 'creative')
                    <p class="mb-3 mb-md-4">Creative writing workshop</p>
                    @else
                    <p class="mb-3 mb-md-4">{{$job->subject ?? '-'}}</p>
                    @endif
                    <p class="fw-bold mb-1">STATE</p>
                    <p class="mb-3 mb-md-4">{{$job->parent->parent_state ?? '-'}}</p>
                    @if (!empty($job->special_request_content))
                    <p class="fw-bold mb-1">SPECIAL REQUIREMENT</p>
                    <p class="mb-3 mb-md-4">{{$job->special_request_content ?? '-'}}</p>
                    @endif
                    @if ($job->session_type_id == 2)
                    <p class="fw-bold mb-3 mb-md-4">ONLINE STUDENT</p>
                    @else
                    <p class="fw-bold mb-1">LOCATION</p>
                    <p class="mb-3 mb-md-4">{{$job->location ?? '-'}}</p>
                    @endif
                    <p class="fw-bold mb-1">INFO</p>
                    @if ($job->job_type == 'creative')
                    <p class="mb-3 mb-md-4">This is a creative writing workshop, funded by the NSW Creative Kids program. It is a one-time creative writing lesson that runs for 1.5 hours either in the student’s home or online. We have a full lesson plan and training for you to follow in Tutorhub. Payment rates are $50 for online delivery or $65 for in-home which will be paid to you manually within 7 days of you confirming the lesson in your dashboard. You can learn more about the Creative Kids workshops in Tutorhub.</p>
                    @else
                    <p class="mb-3 mb-md-4">{!! nl2br($job->job_notes) ?? '-' !!}</p>
                    @endif
                    <div class="mb-3 mb-md-4">
                        <p class="fw-bold mb-1">AVAILABILITIES</p>
                        @forelse ($job->availabilities1 as $av_str)
                        <div>{{$av_str}}</div>
                        @empty
                        <p class="mb-0">-</p>
                        @endforelse
                    </div>
                    <div id="map" class="col-md-9 mx-auto {{ $job->session_type_id == 2 ? 'd-none' : ''}}" style="height:200px"></div>
                </div>
            </div>
        </div>
        <div class="container-fluid position-fixed bottom-0 d-flex justify-content-center align-items-center px-0">
            <button type="button" class="btn btn-warning text-white flex-fill fs-4" x-on:click="loginAndAccept">LOGIN & ACCEPT</button>
            <!-- <button type="button" class="btn btn-dark flex-fill align-self-stretch fs-4" >DECLINE</button> -->
        </div>
    </div>
</div>

<script>
    function student_opportunity(){
        let coords = @json($coords);
        var latLng = new google.maps.LatLng(coords.lat, coords.lon);

        var mapOptions = {
            center: latLng,
            zoom: 15,
            mapId: "DEMO_MAP_ID"
            //mapTypeId: google.maps.MapTypeId.ROADMAP
        };

        var map = new google.maps.Map(document.getElementById('map'), mapOptions);
        const beachFlagImg = document.createElement("img")
        beachFlagImg.src = "/images/map-pin-4.png"
        beachFlagImg.width = "40"

        var marker = new google.maps.marker.AdvancedMarkerElement({
            position: latLng,
            map: map,
            title: 'Student Opportunity',
            content: beachFlagImg,
        });
        marker.content.classList.add("bounce")
    }
    
    document.addEventListener('alpine:init', () => {
        Alpine.data('student_opportunity_init', () => ({
            loginAndAccept() {
                location.href = "/jobs/{{$job->id}}";
            }
        }))
    })
</script>