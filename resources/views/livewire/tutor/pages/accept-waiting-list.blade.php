<div>
    @section('title')
    Tutoring for student
    @endsection
    @section('description')
    @endsection

    <div class="text-center" style="height:100vh;" x-data="accept_waiting_list_init">
        <div class="container">
            @if (empty($waiting_lead_offer))
            <h1 class="py-4 text-center">Oops - this tutor opportunity has expired</h1>
            <p class="text-center py-3">But don't worry - we will be in touch as soon as a great new tutor is available for you.</p>
            <p class="text-center py-3">If you have any questions, don't hesitate to <a href="{{env('MAIN_SITE')}}/#contact-section">get in touch!</a></p>
            @else
            <template x-if="!is_accepted && !is_declined">
                <div>
                    <h2 class="mt-4 mb-3">We have a great tutor for Jasmin</h2>
                    <div class="row d-flex justify-content-center align-items-center h-100">
                        <div class="col col-md-9 col-lg-7 col-xl-5">
                            <div class="card shadow-lg" style="border-radius: 15px;">
                                <div class="card-body p-4">
                                    <div class="d-flex text-black justify-content-center align-items-center">
                                        <div class="">
                                            <img src="{{asset($tutor->getPhoto())}}" alt="Profile image" class="img-fluid" style="width: 150px; border-radius: 50%;">
                                        </div>
                                        <div class="text-center ms-3">
                                            <h4 class="mb-4">{{$tutor->tutor_name}}</h4>
                                            <div>
                                                <p class="mb-1" style="color: #2b2a2a;">{{$job->subject}}</p>
                                                <br>
                                                <p class="mb-1" style="color: #2b2a2a;"><a style="width: 100%;" class="btn btn-warning text-white fw-bold" href="{{env('TUTOR')}}/tutor/{{$tutor->id}}" target="_blank">View full profile here</a></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div>
                                <p class="mb-1">We know that {{$tutor->first_name}} will be able to bring out the best in {{$child->child_name}}.</p><br>
                                <p>To schedule your first lesson, please select a time from the following:</p>
                                <select name="job_availability" id="job_availability" class="form-select mb-2" wire:model="job_availability" x-model="job_availability">
                                    <option value=""></option>
                                    @forelse ($job->formatted_date as $av => $item)
                                    <option value="{{$av}}">{{$item['date']}}</option>
                                    @empty
                                    @endforelse
                                </select>
                                <div class="mb-2">
                                    @forelse ($job->formatted_date as $av => $item)
                                    <div class="" x-show="job_availability == '{{$av}}'">Your first session will be on <b>{{$item['full_date']}}</b></div>
                                    @empty
                                    @endforelse
                                </div>
                                <p>Location: {{$job->location}}</p>
                                <div class="text-center">
                                    <button type="button" class="btn btn-warning text-white fw-bold" id="accept-session" :disabled="job_availability == ''" x-on:click="acceptJobFromParent">Submit</button>
                                    <p style="margin-top: 20px;"><a href="javascript:void(0)" id="decline-tutor">or decline this tutor but remain on the waiting list</a></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
            <template x-if="!!is_accepted">
                <h2 class="mt-4">Thank you, your first lesson details will be emailed to you shortly.</h2>
            </template>
            <template x-if="!!is_declined">
                <div class="pt-4">
                    <h2>Thank you!</h2>
                    <h3>You will remain on our priority waiting list and we will be in touch as soon as a new tutor becomes available</h3>
                </div>
            </template>
            @endif
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('accept_waiting_list_init', () => ({
            job_availability: '',
            is_accepted: false,
            is_declined: false,
            async acceptJobFromParent() {
                let result = await @this.call('acceptJobFromParent', this.job_availability);
                if (!!result) this.is_accepted = true;
                else toastr.error('Error - Something went wrong.')
            },
            async declineJobFromParent() {
                let result = await @this.call('declineJobFromParent');
                if (!!result) this.is_declined = true;
                else toastr.error('Error - Something went wrong.')
            }
        }))
    })
</script>