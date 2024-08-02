<div>
    @php
    $title = "Jobs map";
    $breadcrumbs = ["Jobs", "Map"];
    @endphp
    <x-custom-header :title="$title" :breadcrumbs="$breadcrumbs" />

    <div class="row" x-data="jobs_map_init">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <h3 class="mb-3">All jobs offer</h3>
                    <p class="mb-0">Here you will find all students currently seeking a tutor.</p>
                    <p>If a date is in the past or you can work with them but on a different day/time, speak with our team on chat or via email and we can reach out to the parent.</p>
                    @if (!$tutor->have_wwcc)
                    <div class="alert alert-warning" role="alert">
                        <strong>Warning - </strong>You do not have a valid Working With Children Check or application number on file, and can therefore not accept jobs. Update your WWCC details <a href="{{route('tutor.your-detail.update-detail')}}" wire:navigate>here</a>
                    </div>
                    @endif
                    @if (!$tutor->accept_job_status)
                    <div class="alert alert-warning" role="alert">
                        <strong>Please note - </strong>Please get in touch via live chat if you wish to work with a student listed here.</a>
                    </div>
                    @endif
                </div>
            </div>
            <div id="map" class="w-100" style="min-height:500px;">
            </div>
        </div>
    </div>
</div>

<script>
    var tutor_jobs_map = null;
    var markers = {};
    var infowindows = {};

    var jobs_map_init = () => {
        Alpine.data('jobs_map_init', () => ({
            jobs: [],
            init() {
                this.initMap();
            },
            initMap() {
                console.log('jobs_map_init');
                tutor_jobs_map = new google.maps.Map(document.getElementById('map'), {
                    zoom: 10,
                    mapId: "DEMO_MAP_ID"
                });
                let jobs = @json($jobs);
                let coords = @json($coords);
                if (jobs.length == 0) return;

                var latLng = new google.maps.LatLng(coords.lat ?? -33.788837, coords.lon ?? 151.2841562);
                var directionsDisplay = new google.maps.DirectionsRenderer({
                    suppressMarkers: true
                });
                var directionsService = new google.maps.DirectionsService();

                // var mapOptions = {
                //     center: latLng,
                //     zoom: 15,
                //     //mapTypeId: google.maps.MapTypeId.ROADMAP
                // };
                // var map = new google.maps.Map(document.getElementById('map'), mapOptions);
                // directionsDisplay.setMap(map);

                directionsDisplay.setMap(tutor_jobs_map);
                tutor_jobs_map.setCenter(latLng);
                const beachFlagImg = document.createElement("img")
                beachFlagImg.src = "/images/map-pin-4.png"
                beachFlagImg.width = "40"
                var marker = new google.maps.marker.AdvancedMarkerElement({
                    position: latLng,
                    map: tutor_jobs_map,
                    title: 'You are here',
                    content: beachFlagImg,
                });

                $.each(jobs, (ind, val) => {
                    this.setJobMarker(val, tutor_jobs_map, coords, directionsService, directionsDisplay);
                });
            },
            //Maps functions
            setRoute(directionsService, directionsDisplay, start, end, map) {
                var request = {
                    origin: start,
                    destination: end,
                    travelMode: google.maps.TravelMode.DRIVING
                };

                directionsService.route(request, function(response, status) {
                    if (status == google.maps.DirectionsStatus.OK) {
                        directionsDisplay.setDirections(response);
                        directionsDisplay.setMap(map);
                    }
                });
            },
            setJobMarker(job, map, tutor_coord, directionsService, directionsDisplay) {
                var can_accept_job = '';
                var can_accept_job_reason = '';
                
                if (job.can_accept_job == 'false') {
                    can_accept_job = 'disabled="disabled"';
                    if (job.can_accept_job_reason) {
                        can_accept_job_reason = `<div class="text-danger mb-1">${job.can_accept_job_reason}</div>`;
                    }
                }

                if (typeof job.coords.lat != 'undefined' && typeof job.coords.lon != 'undefined') {
                    const beachFlagImg = document.createElement("img")
                    beachFlagImg.src = '/images/map-pin-1.png';
                    beachFlagImg.width = "40"
                    var hot_job = '';
                    if (job.job_type == 'hot') {
                        beachFlagImg.src = '/images/map-pin-2.png';
                        hot_job = `
                            <div class="col-md-9 hot-icon hot-lead mb-2" style="float:none; margin: 0 auto;position: relative; width: 160px;height: auto;">
                                <a href="https://tutorhub.alchemytuition.com.au/hotjobs" target="_blank">
                                    <img src="/images/hotlead.jpg" style="width:100%;"/></a>
                                <span class="position-absolute fw-bold text-white" style="right: 15%; top: 100%;">+<span class="hot-lead-amount-text">${job.job_offer_price}'</span>/H</span>
                            </div>`;
                    }
                    if (job.job_type == 'replacement') {
                        pinColor = '/images/map-pin-3.png';
                    }
                    markers['marker_' + job.id] = new google.maps.marker.AdvancedMarkerElement({
                        position: new google.maps.LatLng(job.coords.lat, job.coords.lon),
                        map: map,
                        title: job.subject + ' / ' + job.location,
                        content: beachFlagImg,
                    });

                    var contentString = `
                        <div class="marker-${job.id}-content">
                            <h5 class="mt-0">${job.subject} / ${job.location}</h5>
                            <div class="marker-body">
                                <div>Year: ${job.child.child_year}</div>
                                <div class="mb-2">${job.distance}km away from you</div>
                                ${hot_job}
                                ${can_accept_job_reason}
                                <button type="button" class="btn btn-primary btn-success" ${can_accept_job} job-id="${job.id}"><a href="/jobs/${job.id}" wire:navigate>Details</a></button>&nbsp;
                            </div>
                        </div>`;

                    infowindows['infowindow_' + job.id] = new google.maps.InfoWindow({
                        content: contentString
                    });

                    markers['marker_' + job.id].addListener('click', () => {
                        infowindows['infowindow_' + job.id].open(map, markers['marker_' + job.id]);
                        this.setRoute(directionsService, directionsDisplay, new google.maps.LatLng(tutor_coord.lat, tutor_coord.lon), new google.maps.LatLng(job.coords.lat, job.coords.lon), map);
                    });
                }
            }
        }))
    };

    if (typeof Alpine == 'undefined') {
        document.addEventListener('alpine:init', () => {
            jobs_map_init();
        });
    } else {
        jobs_map_init();
    }
</script>