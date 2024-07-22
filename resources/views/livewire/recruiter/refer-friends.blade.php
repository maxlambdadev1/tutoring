<div>
    @php
    $title = "Home";
    $breadcrumbs = ["Dashboard"];
    @endphp
    <x-custom-header :title="$title" :breadcrumbs="$breadcrumbs" />

    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="text-center">
                        <h3 class="fst-italic mb-4" style="font-family: 'Playfair Display', serif;">Your unique referral code is: {{$recruiter->referral_key}}</h3>
                        <p class="mb-3">Send your referrals to <a href="https://www.alchemy.jobs" target="_blank" >www.alchemy.jobs</a> with your unique referral code. When they use it, you’ll see them appear above along with the status of their application.
                        </p>
                    </div>
                    <div class="fw-bold mt-4">Your referrals</div>
                    <hr />
                    <div class="mb-3">
                        @forelse ($referrals as $referral)
                        <div class="d-flex justify-content-between align-items-center p-2 {{$referral->application_status->application_status == 5 ? 'bg-warning text-white' : ''}}">
                            <div class="">
                                <div class="fw-bold">{{$referral->tutor_first_name}} {{$referral->tutor_last_name}}</div>
                                <p class="mb-0 text-muted">{{$referral->date_submitted_ampm}}</p>
                            </div>
                            <div class="fw-bold">{{$application_status_arr[$referral->application_status->application_status]}}</div>
                        </div>
                        @empty
                        No one yet - start spreading the word!
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>