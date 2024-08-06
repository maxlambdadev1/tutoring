<div>
    @php
    $title = "Find tutor";
    $breadcrumbs = ["Leads", "Find tutor"];
    @endphp
    <x-custom-header :title="$title" :breadcrumbs="$breadcrumbs" />


    <div class="row" x-data="find_tutor_init">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-4 mb-2">
                                    <label for="state" class="form-label">State</label>
                                    <select name="state" id="state" class="form-select" wire:model="state" x-model="state">
                                        <option value=""></option>
                                        @forelse ($states as $state)
                                        <option value="{{$state->name}}">{{$state->name}}</option>
                                        @empty
                                        @endforelse
                                    </select>
                                </div>
                                <div class="col-md-8"></div>
                                <div class="col-12 subjects">
                                    <label class="custom_label">Subjects</label>
                                    <ul id="subjects mb-0">
                                        @foreach ($all_subjects as $subject)
                                        <template x-if="state == '{{$subject->state->name}}'">
                                            <li class="d-inline-block me-3">
                                                <input type="checkbox" id="subjects_{{$subject->id}}" value="{{$subject->name}}" wire:model="subjects" x-model="subjects">
                                                <label for="subjects_{{$subject->id}}">{{$subject->name}}</label>
                                            </li>
                                        </template>
                                        @endforeach
                                    </ul>
                                    <label class="error subject_error" x-ref="subjects_error" style="display:none;">This field is required</label>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <label for="suburb" class="form-label">Suburb/Postcode</label>
                                    <input type="text" class="form-control " name="suburb" id="suburb" wire:model="suburb" x-model="suburb">
                                </div>
                                <div class="col-md-4 mb-2">
                                    <div class="mb-1">Preferred Gender</div>
                                    <div class="form-check mb-1">
                                        <input type="radio" id="gender1" name="gender" class="form-check-input" value="" x-model="gender" wire:model="gender">
                                        <label class="form-check-label" for="gender1">Any</label>
                                    </div>
                                    <div class="form-check mb-1">
                                        <input type="radio" id="gender2" name="gender" class="form-check-input" value="Male" x-model="gender" wire:model="gender">
                                        <label class="form-check-label" for="gender2">Male</label>
                                    </div>
                                    <div class="form-check mb-1">
                                        <input type="radio" id="gender3" name="gender" class="form-check-input" value="Female" x-model="gender" wire:model="gender">
                                        <label class="form-check-label" for="gender3">Female</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-2">
                                        <input type="checkbox" id="vaccinated" wire:model="vaccinated" x-model="vaccinated">
                                        <label for="vaccinated">Only vaccinated tutors</label>
                                    </div>
                                    <div class="mb-2">
                                        <input type="checkbox" id="experienced" wire:model="experienced" x-model="experienced">
                                        <label for="experienced">Only experienced tutors</label>
                                    </div>
                                    <div class="mb-2">
                                        <input type="checkbox" id="seeking_students" wire:model="seeking_students" x-model="seeking_students">
                                        <label for="seeking_students">Only actively seeking student</label>
                                    </div>
                                    <div class="mb-2">
                                        <input type="checkbox" id="non_metro_tutors" wire:model="non_metro_tutors" x-model="non_metro_tutors">
                                        <label for="non_metro_tutors">Only Non-Metro tutors</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="availabilities_wrapper mb-3 px-3">
                                <div class="row">
                                    @php $i = 0; $temp_av = []; @endphp
                                    @foreach ($total_availabilities as $item)
                                    <div class="col-md text-center" data-day="{{$item->name}}">
                                        <h5>{{$item->name}}</h5>
                                        @forelse ($item->getAvailabilitiesName() as $ele)
                                        @php
                                        $avail_hour = $item->short_name . '-' . $ele;
                                        $i++;
                                        $temp_av[] = $avail_hour;
                                        @endphp
                                        <div class="avail_hours p-0 w-100">
                                            <input type="checkbox" class="d-none" id="availability-{{$i}}" value="{{$avail_hour}}" x-model="availabilities" wire:model="availabilities" />
                                            <label for="availability-{{$i}}" class="text-center py-1 w-100">{{$ele}}</label>
                                        </div>
                                        @empty
                                        <div>Not exist</div>
                                        @endforelse
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="col-12 text-center">
                            <button x-show="!loading"  type="button" class="btn btn-primary " x-on:click="findTutors">Search</button>
                            <button x-show="loading"class="btn btn-primary" type="button" disabled>
                                <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                                Searching...
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-body">
                    @if (!empty($search_input))
                    <livewire:admin.leads.find-tutor-table :search_input="$search_input"  key="{{rand(0,9999)}}"/>
                    @else
                    <p class="text-center my-5">There are no results.</p>
                    @endif
                    <div id="map" x-show="tutors.length > 0" class="w-100 mt-3" style="min-height:500px;">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var tutor_jobs_map = null;
    var markers = {};
    var infowindows = {};

    var find_tutor_init = () => {
        Alpine.data('find_tutor_init', () => ({
            state: '',
            subjects: [],
            suburb: '',
            gender: '',
            vaccinated: false,
            experienced: false,
            seeking_students: false,
            non_metro_tutors: false,
            availabilities: [],
            loading: false,
            tutors: [],
            coords: {},
            async findTutors() {
                this.loading = true; 
                let result = await @this.call('findTutors');
                this.tutors = result.tutors ?? [];
                this.coords = result.coords ?? [];
                this.loading = false;
                console.log('aaa', this.tutors, this.coords);
                this.initMap();
            }, 
            initMap() {
                console.log('jobs_map_init');
                let tutors = this.tutors;
                tutor_jobs_map = new google.maps.Map(document.getElementById('map'), {
                    zoom: 10,
                    mapId: "DEMO_MAP_ID"
                });
                if (tutors.length == 0) return;

                let coords = this.coords;
                var latLng = new google.maps.LatLng(coords.lat ?? -33.788837, coords.lon ?? 151.2841562);
                var directionsDisplay = new google.maps.DirectionsRenderer({
                    suppressMarkers: true
                });

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

                $.each(tutors, (ind, val) => {
                    this.setTutorMarker(val, tutor_jobs_map);
                });
            },
            setTutorMarker(tutor, map) {
                if (typeof tutor.lat != 'undefined' && typeof tutor.lon != 'undefined') {
                    const beachFlagImg = document.createElement("img")
                    if (!!tutor.accept_job_status) beachFlagImg.src = 'https://maps.gstatic.com/mapfiles/ridefinder-images/mm_20_orange.png';
                    else beachFlagImg.src = 'https://maps.gstatic.com/mapfiles/ridefinder-images/mm_20_red.png';
                 
                    markers['marker_' + tutor.id] = new google.maps.marker.AdvancedMarkerElement({
                        position: new google.maps.LatLng(tutor.lat, tutor.lon),
                        map: map,
                        title: tutor.tutor_name,
                        content: beachFlagImg,
                    });

                    var contentString = `
                        <div class="marker-${tutor.id}-content">
                            <div class="marker-body">
                                <div>Name: ${tutor.tutor_name ?? ''}</div>
                                <div>Email: ${tutor.tutor_email ?? ''}</div>
                                <div>Phone: ${tutor.tutor_phone ?? ''}</div>
                            </div>
                        </div>`;

                    infowindows['infowindow_' + tutor.id] = new google.maps.InfoWindow({
                        content: contentString
                    });

                    markers['marker_' + tutor.id].addListener('click', () => {
                        infowindows['infowindow_' + tutor.id].open(map, markers['marker_' + tutor.id]);
                    });
                }
            },
            seeOnMap(tutor_id) {
                if (!!infowindows['infowindow_' + tutor_id]) {
                    infowindows['infowindow_' + tutor_id].open(map, markers['marker_' + tutor_id]);
                    location.href = '#map';
                }
            }       
        }))
    };

    if (typeof Alpine == 'undefined') {
        document.addEventListener('alpine:init', () => {
            find_tutor_init();
        });
    } else {
        find_tutor_init();
    }
</script>