<div>
    @php
    $title = "Refer friends";
    $breadcrumbs = ["Refer friends"];
    @endphp
    <x-custom-header :title="$title" :breadcrumbs="$breadcrumbs" />

    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="text-center">
                        <h2 class="fw-bold mb-4">Refer your friends and get paid!</h2>
                        <h3 class="fst-italic mb-4" style="font-family: 'Playfair Display', serif;">Your unique referral code is: {{$tutor->referral_key}}</h3>
                        <p class="fw-bold mb-3">Earn ${{$referral_amount}} for every friend you refer to tutor with us, paid once they have successfully registered in to our system.</p>
                        <p class="mb-3">Ask them to use your referral code in the application form at www.alchemy.jobs - this speeds up the application process and gives them extra credit because we know you referred them!</p>
                        <span class="mb-3">
                            <a href="https://alchemytuition.com.au/tutor-referral/" target="_blank"><strong>Check out our referral kit here</strong></a>
                            - filled with designs and templates for you to use on social media, SMS and email.
                        </span>
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