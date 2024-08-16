<div x-data="replacement_tutor_init">
    @section('title')
    Alchemy Tuition
    @endsection
    @section('description')
    @endsection
    @section('script')
    <script src="https://maps.googleapis.com/maps/api/js?libraries=maps,places,marker&key=AIzaSyAOIopVJmkbjQFH8B9Sy3RpZLJzUQGjHnY&loading=async&callback=replacement_tutor_google_init" async defer></script>
    @endsection

    <div class="" style="height:100vh;">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    @if ($type == 'replacement-parent' || $type == 'replacement-parent-temp')
                    <div x-show="!result" class="mt-4 mt-md-5">
                        <h2 class="text-center mb-4">Let's organise a new tutor for {{$child->child_name ?? ''}} </h2>
                        <p class="text-center mb-0">Please let us know the best day and time for {{$child->first_name ?? ''}}'s sessions and any additional requests you have for your new tutor.</p>
                        <p class="text-center mb-4">If this is a replacement tutor, your previous tutor would have provided us with detailed handover notes that will be provided to the new tutor we organise for you.</p>
                        <form action="#" id="replacement_form">
                            <div class="mb-3">
                                <label for="grade" class="custom_label">What grade are they in? <span style="color: red;">*</span></label>
                                <select name="grade" id="grade" class="form-control form-select" wire:model="grade" x-model="grade">
                                    <option value=""></option>
                                    @foreach ($grades as $grade)
                                    <option value="{{$grade->name}}">{{$grade->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="custom_label mb-0">Availabilities: <span style="color: red;">*</span></label>
                                <p>Please select all the days and times that could work for lessons. The more options you provide, the faster we will be able to organise the right tutor for you!</p>
                                <div class="availabilities_wrapper mb-4 px-3">
                                    <div class="row">
                                        @php $i = 0; @endphp
                                        @foreach ($total_availabilities as $item)
                                        <div class="col-md text-center" data-day="{{$item->name}}">
                                            <h5>{{$item->name}}</h5>
                                            @forelse ($item->getAvailabilitiesName() as $ele)
                                            @php
                                            $avail_hour = $item->short_name . '-' . $ele;
                                            $i++;
                                            @endphp
                                            <div class="avail_hours p-0 w-100">
                                                <input type="checkbox" class="d-none" id="availability-{{$i}}" value="{{$avail_hour}}" wire:model="availabilities" x-model="availabilities" />
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
                            <div class="col-md-12 mb-3">
                                <label for="student_notes" class="custom_label">How can we help?</label>
                                <p class="sub_label">Please provide as much info about your child as you can - why you are seeking a tutor, if and what struggles they have at school and any other details that may be relevant in matching them up with the right tutor.</p>
                                <textarea name="student_notes" class="form-control" id="student_notes" cols="30" rows="5" wire:model="student_notes" x-model="student_notes"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="start_date" class="custom_label">When would you like to start? <span style="color: red;">*</span></label>
                                <select name="start_date" id="start_date" class="form-control form-select" wire:model="start_date" x-model="start_date">
                                    <option value=""></option>
                                    <option value="ASAP">ASAP</option>
                                    <option value="At a later date">At a later date</option>
                                </select>
                            </div>
                            <div class="mb-3" x-show="start_date == 'At a later date'" id="container_start_date_picker">
                                <label for="start_date_picker" class="custom_label">When is the soonest you could begin? <span style="color: red;">*</span></label>
                                <input type="text" name="start_date_picker" class="form-control start_date" id="start_date_picker" wire:model="start_date_picker" x-model="start_date_picker">
                            </div>
                            <p><strong>How would you like to have the lessons? <span style="color: red;">*</span></strong></p>
                            <div class="form-check mb-2">
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input" id="face_to_face" name="session_type_id" value="1" wire:model="session_type_id" x-model="session_type_id">Face to face
                                </label>
                            </div>
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input" id="online" name="session_type_id" value="2" wire:model="session_type_id" x-model="session_type_id">Online
                                </label>
                            </div>
                            <div class="address mt-3" x-show="session_type_id == 1">
                                <label for="address" class="custom_label">What is your address? <span style="color: red;">*</span></label>
                                <input type="text" name="address" class="form-control" id="address" placeholder="Enter a location" wire:model="address" x-model="address">
                            </div>
                            <div class="online_desc mt-3" x-show="session_type_id == 2">
                                <p>Awesome! Once we have lined up the perfect tutor for your child, we will send you a unique URL for our online platform – you will be able to connect with your tutor in our online one-on-one classroom from any device!</p>
                            </div>
                        </form>
                    </div>
                    @elseif ($type == 'replacement-tutor')
                    <div x-show="!result" class="mt-4 mt-md-5">
                        <h2 class="text-center mb-4">Replacement tutor for {{$child->child_name ?? ''}} </h2>
                        <p class="text-center mb-0">Please enter your proposed last session date with the student and some notes for their new tutor.</p>
                        <p class="text-center mb-4">Include any tips or advice you think will be valuable to them, like what you have been working on or what the student struggles with most.</p>
                        <form action="#" id="replacement_tutor_form">
                            <div class="mb-3"id="container_session_date">
                                <label for="session_date" class="custom_label">Date of your last session with student: <span style="color: red;">*</span></label>
                                <input type="text" name="session_date" class="form-control session_date" id="session_date" wire:model="session_date" x-model="session_date">
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="notes" class="custom_label">Handover notes for new tutor: <span style="color: red;">*</span></label>
                                <textarea name="notes" class="form-control" id="notes" cols="30" rows="5" wire:model="notes" x-model="notes"></textarea>
                            </div>
                        </form>
                    </div>
                    @endif
                    <div class="mt-4 mb-4 text-center" x-show="!result">
                        <button type="button" class="btn btn-warning text-white col-md-3 mb-2" x-on:click="submit" x-show="!loading" >Submit</button>
                        <button type="button" class="btn btn-warning text-white col-md-3 mb-2" x-show="loading" disabled>
                            <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                            Submitting...</button>
                    </div>
                    <div x-show="!!result" class="mt-4 mt-md-5 text-center">
                        <h2 class="mb-3">Thank you</h2>
                        <p>Your details have been submitted.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let type = @json($type);

    var replacement_tutor_google_init = function() {
        var options = {
            componentRestrictions: {
                country: "au"
            }
        };
        var input = document.getElementById('address');
        if (input != null) new google.maps.places.Autocomplete(input, options);
    }

    document.addEventListener('alpine:init', () => {
        Alpine.data('replacement_tutor_init', () => ({
            grade: '',
            availabilities: [],
            student_notes: '',
            start_date: '',
            start_date_picker: '',
            session_type_id: '',
            address: '',
            session_date: '',
            notes: '',
            loading: false,
            result: false,
            init() {
                let temp = this;
                $('#start_date_picker').datepicker({
                    autoclose: true,
                    format: 'dd/mm/yyyy',
                    startDate: new Date(),
                    todayHighlight: true,
                }).on('changeDate', function(e) {
                    var selectedDate = e.format(0, 'dd/mm/yyyy');
                    @this.set('start_date_picker', selectedDate);
                    temp.start_date_picker = selectedDate;
                });
                $('#session_date').datepicker({
                    autoclose: true,
                    format: 'dd/mm/yyyy',
                    startDate: new Date(),
                    todayHighlight: true,
                }).on('changeDate', function(e) {
                    var selectedDate = e.format(0, 'dd/mm/yyyy');
                    @this.set('session_date', selectedDate);
                    temp.session_date = selectedDate;
                });

            },
            async submit() {
                if (type == 'replacement-parent' || type == 'replacement-parent-temp') {
                    $('#replacement_form').validate({
                        rules: {
                            grade: 'required',
                            start_date: 'required',
                            start_date_picker: 'required',
                            address: 'required',
                        }
                    });
                    if ($('#replacement_form').valid()) {
                        this.loading = true;
                        let result = await @this.call('replacementParent', $('#address').val());
                        if (result) this.result = result;
                        this.loading = false;
                    }
                }
                else if (type == 'replacement-tutor') {
                    $('#replacement_tutor_form').validate({
                        rules: {
                            session_date: 'required',
                            notes: 'required',
                        }
                    });
                    if ($('#replacement_tutor_form').valid()) {
                        this.loading = true;
                        let result = await @this.call('replacementExTutor');
                        if (result) this.result = result;
                        this.loading = false;
                    }
                }
            },
        }))
    })
</script>